<?php

namespace App\Console\Commands;

use App\Mail\EventEvaluationReminderMail;
use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Registration;
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

  protected $description = 'Send event evaluation reminders one day after events end';

  public function handle(): int
  {
    $hasReminderTracking = Schema::hasColumn('registrations', 'evaluation_reminder_sent_at')
      && Schema::hasColumn('registrations', 'evaluation_reminder_status');

    if (! $hasReminderTracking) {
      $this->error('Reminder tracking columns are missing in registrations. Run php artisan migrate before sending reminders.');
      return self::FAILURE;
    }

    $force = (bool) $this->option('force');
    $eligibleEndDate = $force ? now()->toDateString() : now()->subDay()->toDateString();
    $eventFilter = $this->option('event_id');

    $eventsQuery = Event::query()
      ->where(function ($query) {
        $query->whereNull('status')
          ->orWhere('status', '!=', 'archived');
      })
      ->where(function ($query) use ($eligibleEndDate) {
        $query
          ->where(function ($innerQuery) use ($eligibleEndDate) {
            $innerQuery->whereNotNull('end_date')
              ->whereDate('end_date', '<=', $eligibleEndDate);
          })
          ->orWhere(function ($innerQuery) use ($eligibleEndDate) {
            $innerQuery->whereNull('end_date')
              ->whereDate('event_date', '<=', $eligibleEndDate);
          });
      });

    if ($eventFilter !== null && $eventFilter !== '') {
      $eventsQuery->where('event_id', (int) $eventFilter);
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
        ->whereIn('status', ['approved', 'pending'])
        ->whereNull('evaluation_reminder_sent_at')
        ->whereHas('user', function ($query) {
          $query->where('role', 'participant');
        })
        ->with(['user:id,firstname,lastname,email'])
        ->orderBy('registration_id')
        ->chunkById(100, function ($registrations) use ($event, &$sentCount, &$skippedCount, &$failedCount): void {
          foreach ($registrations as $registration) {
            if ($this->hasAlreadyEvaluated($registration, $event->event_id)) {
              $this->markReminder($registration, status: 'skipped_evaluated', sentAt: now());
              $skippedCount++;
              continue;
            }

            $recipientEmail = strtolower(trim((string) ($registration->user->email ?? '')));
            if ($recipientEmail === '') {
              $this->markReminder($registration, status: 'skipped_no_email', sentAt: now());
              $skippedCount++;
              continue;
            }

            $fullName = trim((string) ($registration->user->firstname ?? '') . ' ' . (string) ($registration->user->lastname ?? ''));
            $eventEndDate = $this->resolveEventEndDate($event);
            $evaluationUrl = $this->buildEvaluationUrl((int) $event->event_id);

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

  private function hasAlreadyEvaluated(Registration $registration, int $eventId): bool
  {
    $hasEventId = Schema::hasColumn('evaluations', 'event_id');
    $hasRegistrationId = Schema::hasColumn('evaluations', 'registration_id');

    if ($hasEventId && $hasRegistrationId) {
      return Evaluation::query()
        ->where('event_id', $eventId)
        ->where('registration_id', $registration->registration_id)
        ->exists();
    }

    $eventPaperId = DB::table('papers')
      ->where('event_id', $eventId)
      ->where('user_id', $registration->user_id)
      ->orderByDesc('paper_id')
      ->value('paper_id');

    if ($eventPaperId === null) {
      return false;
    }

    return Evaluation::query()
      ->where('paper_id', $eventPaperId)
      ->where('evaluator_id', $registration->user_id)
      ->exists();
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
    $endDate = $event->end_date ?: $event->event_date;
    return Carbon::parse((string) $endDate);
  }

  private function buildEvaluationUrl(int $eventId): string
  {
    return url('/?evaluate_event=' . $eventId);
  }
}
