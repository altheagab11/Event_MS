<?php

namespace App\Console\Commands;

use App\Mail\EventEvaluationReminderMail;
use App\Models\Event;
use App\Models\Registration;
use App\Services\EventEvaluationService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SendEventEvaluationRemindersCommand extends Command
{
    protected $signature = 'events:send-evaluation-reminders {--event_id= : Send reminders only for this event_id} {--force : Ignore the one-day waiting window and process ended events immediately}';

    protected $description = 'Send event evaluation reminders to checked-in participants (manual admin action only)';

    public function handle(): int
    {
        $hasReminderTracking = Schema::hasColumn('registrations', 'evaluation_reminder_sent_at')
          && Schema::hasColumn('registrations', 'evaluation_reminder_status');

        if (! $hasReminderTracking) {
            $this->error('Reminder tracking columns are missing in registrations. Run php artisan migrate before sending reminders.');

            return self::FAILURE;
        }

        if (! (bool) $this->option('force')) {
            $this->error('This command must be run with --force from the admin Post-Event Controls action.');

            return self::FAILURE;
        }

        $eventFilter = $this->option('event_id');

        $eventsQuery = Event::query()
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', '!=', 'archived');
            });

        if ($eventFilter !== null && $eventFilter !== '') {
            $eventsQuery->where('event_id', (int) $eventFilter);
        } else {
            $this->error('The --event_id option is required.');

            return self::FAILURE;
        }

        $events = $eventsQuery->get();

        if ($events->isEmpty()) {
            $this->info('No eligible finished events found for evaluation reminders.');

            return self::SUCCESS;
        }

        $sentCount = 0;
        $skippedCount = 0;
        $failedCount = 0;

        foreach ($events as $event) {
            Registration::query()
                ->where('event_id', $event->event_id)
                ->where('status', 'approved')
                ->whereNotNull('event_registrant_id')
                ->whereNull('evaluation_reminder_sent_at')
                ->whereHas('attendance')
                ->where(function ($query) {
                    $query->whereHas('user', function ($userQuery) {
                        $userQuery->where('role', 'participant');
                    })->orWhereNotNull('event_registrant_id');
                })
                ->with([
                    'user:id,firstname,lastname,email',
                    'eventRegistrant:event_registrant_id,first_name,last_name,email',
                ])
                ->orderBy('registration_id')
                ->chunkById(100, function ($registrations) use ($event, &$sentCount, &$skippedCount, &$failedCount): void {
                    $evaluationService = app(EventEvaluationService::class);

                    foreach ($registrations as $registration) {
                        if (! $this->hasCheckedIn($registration, (int) $event->event_id)) {
                            $this->markReminder($registration, status: 'skipped_not_attended', sentAt: now());
                            $skippedCount++;

                            continue;
                        }

                        if ($evaluationService->hasSubmittedEvaluation($registration)) {
                            $this->markReminder($registration, status: 'skipped_evaluated', sentAt: now());
                            $skippedCount++;

                            continue;
                        }

                        $recipientEmail = strtolower(trim((string) ($registration->user?->email ?? $registration->eventRegistrant?->email ?? '')));
                        if ($recipientEmail === '') {
                            $this->markReminder($registration, status: 'skipped_no_email', sentAt: now());
                            $skippedCount++;

                            continue;
                        }

                        $fullName = $registration->user !== null
                          ? trim((string) ($registration->user->firstname ?? '').' '.(string) ($registration->user->lastname ?? ''))
                          : trim((string) ($registration->eventRegistrant?->first_name ?? '').' '.(string) ($registration->eventRegistrant?->last_name ?? ''));
                        $eventEndDate = $this->resolveEventEndDate($event);
                        $evaluationUrl = $evaluationService->buildEvaluationUrl($registration);

                        try {
                            Mail::to($recipientEmail)->send(new EventEvaluationReminderMail(
                                eventName: (string) $event->event_name,
                                fullName: $fullName !== '' ? $fullName : 'Participant',
                                eventEndDate: $eventEndDate->format('F j, Y'),
                                evaluationUrl: $evaluationUrl
                            ));

                            $this->markReminder($registration, status: 'sent', sentAt: now());
                            $sentCount++;
                        } catch (Throwable $exception) {
                            $this->markReminder($registration, status: 'failed', sentAt: null);
                            $failedCount++;

                            Log::error('Failed to send event evaluation reminder.', [
                                'registration_id' => $registration->registration_id,
                                'event_id' => $event->event_id,
                                'email' => $recipientEmail,
                                'error' => $exception->getMessage(),
                            ]);
                        }
                    }
                }, 'registration_id', 'registration_id');
        }

        $this->info("Evaluation reminders processed. Sent: {$sentCount}, Skipped: {$skippedCount}, Failed: {$failedCount}");

        return self::SUCCESS;
    }

    private function markReminder(Registration $registration, string $status, ?Carbon $sentAt): void
    {
        Registration::query()
            ->where('registration_id', $registration->registration_id)
            ->update([
                'evaluation_reminder_status' => $status,
                'evaluation_reminder_sent_at' => $sentAt,
            ]);
    }

    private function resolveEventEndDate(Event $event): Carbon
    {
        return $event->resolveEndAt() ?? now();
    }

    private function hasCheckedIn(Registration $registration, int $eventId): bool
    {
        if ($registration->event_registrant_id === null) {
            return false;
        }

        return DB::table('attendance')
            ->join('event_sessions', 'event_sessions.session_id', '=', 'attendance.session_id')
            ->where('attendance.registration_id', $registration->event_registrant_id)
            ->where('event_sessions.event_id', $eventId)
            ->exists();
    }
}
