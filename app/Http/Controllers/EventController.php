<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Registration;
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

    $events = Event::query()
      ->where(function ($query) {
        $query->whereNull('status')
          ->orWhere('status', '!=', 'archived');
      })
      ->orderBy('event_date')
      ->get()
      ->map(function (Event $event): array {
        $eventDate = $event->event_date instanceof Carbon
          ? $event->event_date
          : Carbon::parse($event->event_date);

        $eventEndDate = $event->end_date instanceof Carbon
          ? $event->end_date
          : Carbon::parse((string) ($event->end_date ?: $event->event_date));

        return [
          'id' => $event->event_id,
          'title' => $event->event_name,
          'type' => (string) $event->event_type === 'Conference' ? 'Conference Event' : 'School Event',
          'date' => $eventDate->format('F j, Y'),
          'month' => $eventDate->month - 1,
          'year' => $eventDate->format('Y'),
          'location' => $event->location ?: 'TBA',
          'attendance_format' => $event->attendance_format ?: 'Not Specified',
          'description' => $event->description ?: 'No description available.',
          'status' => $eventEndDate->copy()->endOfDay()->isPast() ? 'ended' : 'active',
          'image' => $event->banner_url
            ?: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=60',
        ];
      })
      ->values();

    return view('landingpage', [
      'events' => $events,
    ]);
  }

  public function index()
  {
    $events = Event::query()
      ->orderByDesc('event_date')
      ->get();

    $today = Carbon::today();
    $events->each(function (Event $event) use ($today): void {
      $endDate = $event->end_date ?? $event->event_date;
      if ($endDate !== null && ! $endDate instanceof Carbon) {
        $endDate = Carbon::parse((string) $endDate);
      }

      $isArchived = (string) $event->status === 'archived';
      $isDone = ! $isArchived && $endDate instanceof Carbon && $endDate->lt($today);

      $event->setAttribute('computed_status', $isArchived ? 'archived' : ($isDone ? 'done' : 'active'));
      $event->setAttribute('computed_status_label', $isArchived ? 'Archived' : ($isDone ? 'Done' : 'Active'));
    });

    $hasReminderTracking = Schema::hasColumn('registrations', 'evaluation_reminder_sent_at')
      && Schema::hasColumn('registrations', 'evaluation_reminder_status');

    $eventReminderSummary = [];
    if ($hasReminderTracking) {
      $eventReminderSummary = Registration::query()
        ->selectRaw('event_id')
        ->selectRaw("SUM(CASE WHEN status IN ('approved', 'pending') THEN 1 ELSE 0 END) as total_recipients")
        ->selectRaw("SUM(CASE WHEN status IN ('approved', 'pending') AND evaluation_reminder_sent_at IS NOT NULL THEN 1 ELSE 0 END) as sent_count")
        ->selectRaw('SUM(CASE WHEN evaluation_reminder_sent_at IS NOT NULL THEN 1 ELSE 0 END) as any_sent_count')
        ->selectRaw('MAX(evaluation_reminder_sent_at) as last_sent_at')
        ->groupBy('event_id')
        ->get()
        ->mapWithKeys(function ($row): array {
          $sentCount = (int) ($row->sent_count ?? 0);
          $totalRecipients = (int) ($row->total_recipients ?? 0);
          $anySentCount = (int) ($row->any_sent_count ?? 0);
          $lastSentRaw = $row->last_sent_at;

          return [
            (int) $row->event_id => [
              'sent_count' => $sentCount,
              'total_recipients' => $totalRecipients,
              'fully_sent' => $totalRecipients > 0 && $sentCount >= $totalRecipients,
              'any_sent' => $anySentCount > 0,
              'last_sent_at' => $lastSentRaw ? Carbon::parse((string) $lastSentRaw)->format('M d, Y h:i A') : null,
            ],
          ];
        })
        ->all();
    }

    return view('admin.events', [
      'events' => $events,
      'hasReminderTracking' => $hasReminderTracking,
      'eventReminderSummary' => $eventReminderSummary,
    ]);
  }

  public function store(StoreEventRequest $request)
  {
    $payload = $request->safe()->except('banner_image');
    $payload['event_date'] = $payload['start_date'];

    if ($request->hasFile('banner_image')) {
      $this->ensurePublicStorageLinkExists();
      $payload['banner_image'] = $request->file('banner_image')->store('event-banners', 'public');
    }

    Event::query()->create($payload);

    return redirect()
      ->route('admin.events')
      ->with('status', 'Event created successfully.');
  }

  public function update(UpdateEventRequest $request, Event $event): RedirectResponse
  {
    $payload = $request->safe()->except(['banner_image', 'editing_event_id']);
    $payload['event_date'] = $payload['start_date'];

    if ($request->hasFile('banner_image')) {
      $this->ensurePublicStorageLinkExists();
      $payload['banner_image'] = $request->file('banner_image')->store('event-banners', 'public');
      $this->deleteStoredBannerIfLocal($event->banner_image);
    }

    $event->fill($payload)->save();

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

    if ((string) $event->status === 'archived') {
      return redirect()
        ->route('admin.events')
        ->with('status_type', 'warning')
        ->with('status', 'Evaluation reminders are not available for archived events.');
    }

    $eventEndDate = $event->end_date instanceof Carbon
      ? $event->end_date
      : Carbon::parse((string) ($event->end_date ?: $event->event_date));

    if ($eventEndDate->copy()->endOfDay()->isFuture()) {
      return redirect()
        ->route('admin.events')
        ->with('status_type', 'warning')
        ->with('status', 'Evaluation reminders can only be sent after the event has ended.');
    }

    $alreadyProcessed = Registration::query()
      ->where('event_id', $event->event_id)
      ->whereNotNull('evaluation_reminder_sent_at')
      ->exists();

    if ($alreadyProcessed) {
      return redirect()
        ->route('admin.events')
        ->with('status_type', 'warning')
        ->with('status', 'Evaluation reminders were already processed for this event.');
    }

    $hasPendingRecipients = Registration::query()
      ->where('event_id', $event->event_id)
      ->whereIn('status', ['approved', 'pending'])
      ->whereHas('user', function ($query) {
        $query->where('role', 'participant');
      })
      ->whereNull('evaluation_reminder_sent_at')
      ->exists();

    if (! $hasPendingRecipients) {
      return redirect()
        ->route('admin.events')
        ->with('status_type', 'warning')
        ->with('status', 'Evaluation reminders were already sent to all eligible registrants for this event.');
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
        ->with('status', 'Manual reminder run failed. Please check logs and try again.');
    }

    $failedCount = 0;
    if (preg_match('/Failed:\s*(\d+)/', $output, $matches) === 1) {
      $failedCount = (int) ($matches[1] ?? 0);
    }

    $statusType = $failedCount > 0 ? 'warning' : 'success';
    $statusMessage = 'Evaluation reminders processed.';

    return redirect()
      ->route('admin.events')
      ->with('status_type', $statusType)
      ->with('status', $statusMessage);
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
