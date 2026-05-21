<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Registration;
use App\Services\EventOnlineAttendanceTokenService;
use App\Services\LandingAnnouncementService;
use App\Services\PostEventCertificateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function landing()
    {
        if (auth()->check() && auth()->user()?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $eventModels = Event::query()
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', '!=', 'archived');
            })
            ->orderByRaw('COALESCE(start_date, event_date) ASC')
            ->get();

        $announcements = app(LandingAnnouncementService::class)->buildFromEvents($eventModels);

        $events = $eventModels->map(function (Event $event): array {
                $scheduleStart = $event->start_date ?? $event->event_date;
                if ($scheduleStart !== null && ! $scheduleStart instanceof Carbon) {
                    $scheduleStart = Carbon::parse((string) $scheduleStart);
                }

                $cardState = $event->publicRegistrationCardState();

                return [
                    'id' => $event->event_id,
                    'title' => $event->event_name,
                    'type' => (string) $event->event_type === 'Conference' ? 'Conference Event' : 'School Event',
                    'date' => $event->formatted_schedule_range,
                    'month' => $scheduleStart instanceof Carbon ? $scheduleStart->month - 1 : 0,
                    'year' => $scheduleStart instanceof Carbon ? $scheduleStart->format('Y') : '',
                    'location' => $event->location ?: 'TBA',
                    'attendance_format' => $event->attendance_format ?: 'Not Specified',
                    'description' => $event->description ?: 'No description available.',
                    'card_label' => $cardState['label'],
                    'card_state' => $cardState['state'],
                    'can_register' => $cardState['can_register'],
                    'image' => $event->banner_url
                      ?: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=60',
                    'paper_format_url' => $event->paper_format_url,
                ];
            })
            ->values();

        return view('landingpage', [
            'events' => $events,
            'announcements' => $announcements,
        ]);
    }

    public function index()
    {
        $events = Event::query()
            ->orderByDesc('event_id')
            ->get();

        $events->each(function (Event $event): void {
            $event->syncStatusIfEnded();
            $event->applyComputedStatusAttributes();
        });

        $hasReminderTracking = Schema::hasColumn('registrations', 'evaluation_reminder_sent_at')
          && Schema::hasColumn('registrations', 'evaluation_reminder_status');

        $certificateService = app(PostEventCertificateService::class);
        $hasPostEventColumns = Schema::hasColumn('events', 'evaluation_links_sent_at');

        $eventReminderSummary = [];
        if ($hasReminderTracking) {
            $events->each(function (Event $event) use ($certificateService, $hasPostEventColumns, &$eventReminderSummary): void {
                $attendedEligible = $certificateService->countAttendedEligibleRegistrants((int) $event->event_id);
                $evaluationLinksSent = $hasPostEventColumns && $event->evaluation_links_sent_at !== null;

                $event->setAttribute('attended_eligible_count', $attendedEligible);
                $event->setAttribute('evaluation_links_sent', $evaluationLinksSent);
                $event->setAttribute('attendance_certificates_distributed', $hasPostEventColumns && $event->attendance_certificates_distributed_at !== null);

                $eventReminderSummary[(int) $event->event_id] = [
                    'attended_eligible_count' => $attendedEligible,
                    'evaluation_links_sent' => $evaluationLinksSent,
                    'attendance_certificates_distributed' => $hasPostEventColumns && $event->attendance_certificates_distributed_at !== null,
                    'evaluation_links_sent_at' => $evaluationLinksSent
                        ? Carbon::parse((string) $event->evaluation_links_sent_at)->format('M d, Y h:i A')
                        : null,
                ];
            });
        }

        return view('admin.events', [
            'events' => $events,
            'hasReminderTracking' => $hasReminderTracking,
            'eventReminderSummary' => $eventReminderSummary,
        ]);
    }

    public function store(StoreEventRequest $request)
    {
        $payload = $request->safe()->except(['banner_image', 'paper_format_file']);
        $payload = $this->normalizeSchedulePayload($payload);

        if ($request->hasFile('banner_image')) {
            $this->ensurePublicStorageLinkExists();
            $payload['banner_image'] = $request->file('banner_image')->store('event-banners', 'public');
        }

        if ($request->input('event_type') === 'Conference' && $request->hasFile('paper_format_file')) {
            $this->ensurePublicStorageLinkExists();
            $payload['paper_format_file'] = $request->file('paper_format_file')->store('event-paper-formats', 'public');
        }

        $event = Event::query()->create($payload);
        app(EventOnlineAttendanceTokenService::class)->ensureToken($event);

        return redirect()
            ->route('admin.events')
            ->with('status', 'Event created successfully.');
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $payload = $request->safe()->except(['banner_image', 'paper_format_file', 'editing_event_id']);
        $payload = $this->normalizeSchedulePayload($payload);

        if ($request->hasFile('banner_image')) {
            $this->ensurePublicStorageLinkExists();
            $payload['banner_image'] = $request->file('banner_image')->store('event-banners', 'public');
            $this->deleteStoredBannerIfLocal($event->banner_image);
        }

        if ($request->input('event_type') === 'Conference') {
            if ($request->hasFile('paper_format_file')) {
                $this->ensurePublicStorageLinkExists();
                $payload['paper_format_file'] = $request->file('paper_format_file')->store('event-paper-formats', 'public');
                $this->deleteStoredPaperFormatIfLocal($event->paper_format_file);
            }
        } else {
            $this->deleteStoredPaperFormatIfLocal($event->paper_format_file);
            $payload['paper_format_file'] = null;
        }

        $event->fill($payload)->save();
        app(EventOnlineAttendanceTokenService::class)->ensureToken($event);

        return redirect()
            ->route('admin.events')
            ->with('status', 'Event updated successfully.');
    }

    public function archive(Event $event): RedirectResponse
    {
        $event->forceFill(['status' => 'archived'])->save();

        return redirect()
            ->route('admin.events')
            ->with('status', 'Event archived successfully.');
    }

    public function sendEvaluationReminder(Event $event): RedirectResponse
    {
        $hasReminderTracking = Schema::hasColumn('registrations', 'evaluation_reminder_sent_at')
          && Schema::hasColumn('registrations', 'evaluation_reminder_status');

        if (! $hasReminderTracking) {
            return redirect()
                ->route('admin.events')
                ->with('status_type', 'warning')
                ->with('status', 'Reminder tracking columns are missing. Run php artisan migrate first.');
        }

        if (! Schema::hasColumn('events', 'evaluation_links_sent_at')) {
            return redirect()
                ->route('admin.events')
                ->with('status_type', 'warning')
                ->with('status', 'Post-event tracking columns are missing. Run php artisan migrate first.');
        }

        if ((string) $event->status === 'archived') {
            return redirect()
                ->route('admin.events')
                ->with('status_type', 'warning')
                ->with('status', 'Evaluation reminders are not available for archived events.');
        }

        $event->syncStatusIfEnded();

        if (! $event->hasEnded()) {
            return redirect()
                ->route('admin.events')
                ->with('status_type', 'warning')
                ->with('status', 'Evaluation reminders can only be sent after the event has ended.');
        }

        if ($event->evaluation_links_sent_at !== null) {
            return redirect()
                ->route('admin.events')
                ->with('status_type', 'warning')
                ->with('status', 'Evaluation links were already sent for this event.');
        }

        $certificateService = app(PostEventCertificateService::class);
        $attendedEligibleCount = $certificateService->countAttendedEligibleRegistrants((int) $event->event_id);

        if ($attendedEligibleCount === 0) {
            return redirect()
                ->route('admin.events')
                ->with('status_type', 'warning')
                ->with('status', 'No checked-in participants are eligible for evaluation links.');
        }

        $hasPendingRecipients = Registration::query()
            ->where('event_id', $event->event_id)
            ->where('status', 'approved')
            ->whereNotNull('event_registrant_id')
            ->whereHas('attendance')
            ->whereNull('evaluation_reminder_sent_at')
            ->where(function ($query) {
                $query->whereHas('user', function ($userQuery) {
                    $userQuery->where('role', 'participant');
                })->orWhereNotNull('event_registrant_id');
            })
            ->exists();

        if (! $hasPendingRecipients) {
            $event->forceFill(['evaluation_links_sent_at' => now()])->save();
            $certificateCounts = $this->distributeAttendanceCertificatesIfNeeded($event, $certificateService);

            return redirect()
                ->route('admin.events')
                ->with('status_type', 'success')
                ->with('status', $this->buildPostEventSendStatusMessage(0, $certificateCounts));
        }

        $exitCode = Artisan::call('events:send-evaluation-reminders', [
            '--event_id' => (int) $event->event_id,
            '--force' => true,
        ]);

        $output = trim((string) Artisan::output());

        if ($exitCode !== 0) {
            return redirect()
                ->route('admin.events')
                ->with('status_type', 'warning')
                ->with('status', 'Sending evaluation links failed. Please check logs and try again.');
        }

        $event->forceFill(['evaluation_links_sent_at' => now()])->save();

        $failedCount = 0;
        if (preg_match('/Failed:\s*(\d+)/', $output, $matches) === 1) {
            $failedCount = (int) ($matches[1] ?? 0);
        }

        $certificateCounts = $this->distributeAttendanceCertificatesIfNeeded($event, $certificateService);
        $statusType = $failedCount > 0 || $certificateCounts['failed'] > 0 ? 'warning' : 'success';

        return redirect()
            ->route('admin.events')
            ->with('status_type', $statusType)
            ->with('status', $this->buildPostEventSendStatusMessage($failedCount, $certificateCounts));
    }

    /**
     * @return array{attendance: int, skipped: int, failed: int}
     */
    private function distributeAttendanceCertificatesIfNeeded(Event $event, PostEventCertificateService $certificateService): array
    {
        $emptyCounts = ['attendance' => 0, 'skipped' => 0, 'failed' => 0];

        if (! Schema::hasColumn('registrations', 'attendance_certificate_sent_at')
            || ! Schema::hasColumn('events', 'attendance_certificates_distributed_at')) {
            return $emptyCounts;
        }

        if ($event->attendance_certificates_distributed_at !== null) {
            return $emptyCounts;
        }

        $counts = $certificateService->distributeAttendanceCertificatesForEvent($event);
        $event->forceFill(['attendance_certificates_distributed_at' => now()])->save();

        return $counts;
    }

    /**
     * @param  array{attendance: int, skipped: int, failed: int}  $certificateCounts
     */
    private function buildPostEventSendStatusMessage(int $evaluationLinkFailures, array $certificateCounts): string
    {
        $message = 'Evaluation links and attendance certificates sent to checked-in participants.';

        if ($evaluationLinkFailures > 0 || $certificateCounts['failed'] > 0) {
            $message = 'Post-event emails were processed with some failures. Please check logs.';
        }

        return $message;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function normalizeSchedulePayload(array $payload): array
    {
        $startAt = Carbon::parse((string) $payload['start_date']);
        $endAt = Carbon::parse((string) $payload['end_date']);

        $payload['start_date'] = $startAt->format('Y-m-d H:i:s');
        $payload['end_date'] = $endAt->format('Y-m-d H:i:s');
        $payload['event_date'] = $startAt->toDateString();

        return $payload;
    }

    private function deleteStoredPaperFormatIfLocal(?string $paperFormatFile): void
    {
        if (! $paperFormatFile) {
            return;
        }

        if (filter_var($paperFormatFile, FILTER_VALIDATE_URL)) {
            return;
        }

        $relativePath = ltrim($paperFormatFile, '/');
        $relativePath = preg_replace('#^storage/#', '', $relativePath) ?? $relativePath;
        $relativePath = preg_replace('#^public/#', '', $relativePath) ?? $relativePath;

        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    private function deleteStoredBannerIfLocal(?string $bannerImage): void
    {
        if (! $bannerImage) {
            return;
        }

        if (filter_var($bannerImage, FILTER_VALIDATE_URL)) {
            return;
        }

        $relativePath = ltrim($bannerImage, '/');
        $relativePath = preg_replace('#^storage/#', '', $relativePath) ?? $relativePath;
        $relativePath = preg_replace('#^public/#', '', $relativePath) ?? $relativePath;

        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    private function ensurePublicStorageLinkExists(): void
    {
        $publicStoragePath = public_path('storage');
        if (is_link($publicStoragePath) || is_dir($publicStoragePath)) {
            return;
        }

        // Some local setups miss the storage symlink, which makes uploaded files unreachable.
        try {
            Artisan::call('storage:link');
        } catch (\Throwable $exception) {
            // Keep upload flow running; DB can still store relative path.
        }
    }
}
