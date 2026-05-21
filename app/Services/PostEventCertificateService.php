<?php

namespace App\Services;

use App\Mail\EventCertificateMail;
use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Throwable;

class PostEventCertificateService
{
    public function hasCheckedIn(Registration $registration): bool
    {
        if (Schema::hasColumn('registrations', 'attendance_status')
            && strcasecmp((string) ($registration->attendance_status ?? ''), 'Present') === 0) {
            return true;
        }

        if ($registration->event_registrant_id === null) {
            return false;
        }

        return DB::table('attendance')
            ->join('event_sessions', 'event_sessions.session_id', '=', 'attendance.session_id')
            ->where('attendance.registration_id', $registration->event_registrant_id)
            ->where('event_sessions.event_id', $registration->event_id)
            ->exists();
    }

    public function hasCompletedEvaluation(Registration $registration, int $eventId): bool
    {
        if (Schema::hasColumn('registrations', 'evaluation_submitted_at')
            && $registration->evaluation_submitted_at !== null) {
            return true;
        }

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
            ->where(function ($query) use ($registration) {
                if ($registration->user_id !== null) {
                    $query->where('user_id', $registration->user_id);
                } elseif ($registration->event_registrant_id !== null) {
                    $query->where('event_registrant_id', $registration->event_registrant_id);
                }
            })
            ->orderByDesc('paper_id')
            ->value('paper_id');

        if ($eventPaperId === null || $registration->user_id === null) {
            return false;
        }

        return Evaluation::query()
            ->where('paper_id', $eventPaperId)
            ->where('evaluator_id', $registration->user_id)
            ->exists();
    }

    /**
     * @return array{participation: int, skipped: int, failed: int}
     */
    public function issueParticipationAfterEvaluation(Registration $registration, Event $event): array
    {
        if (! $this->hasCheckedIn($registration)) {
            return ['participation' => 0, 'skipped' => 1, 'failed' => 0];
        }

        if (! $this->hasCompletedEvaluation($registration, (int) $event->event_id)) {
            return ['participation' => 0, 'skipped' => 1, 'failed' => 0];
        }

        $counts = ['participation' => 0, 'skipped' => 0, 'failed' => 0];

        if ($registration->participation_certificate_sent_at !== null) {
            $counts['skipped']++;

            return $counts;
        }

        if ($this->sendParticipationCertificate($registration, $event)) {
            $counts['participation']++;
        } else {
            $counts['failed']++;
        }

        return $counts;
    }

    /**
     * @return array{attendance: int, skipped: int, failed: int}
     */
    public function distributeAttendanceCertificatesForEvent(Event $event): array
    {
        $counts = ['attendance' => 0, 'skipped' => 0, 'failed' => 0];

        Registration::query()
            ->where('event_id', $event->event_id)
            ->where('status', 'approved')
            ->whereNotNull('event_registrant_id')
            ->whereNull('attendance_certificate_sent_at')
            ->whereHas('attendance')
            ->with([
                'user:id,firstname,lastname,email',
                'eventRegistrant:event_registrant_id,first_name,last_name,email',
            ])
            ->orderBy('registration_id')
            ->chunkById(100, function ($registrations) use ($event, &$counts): void {
                foreach ($registrations as $registration) {
                    if (! $this->hasCheckedIn($registration)) {
                        $counts['skipped']++;

                        continue;
                    }

                    if ($this->sendAttendanceCertificate($registration, $event)) {
                        $counts['attendance']++;
                    } elseif ($registration->attendance_certificate_sent_at !== null) {
                        $counts['skipped']++;
                    } else {
                        $counts['failed']++;
                    }
                }
            }, 'registration_id', 'registration_id');

        return $counts;
    }

    public function countAttendedEligibleRegistrants(int $eventId): int
    {
        return Registration::query()
            ->where('event_id', $eventId)
            ->where('status', 'approved')
            ->whereNotNull('event_registrant_id')
            ->whereHas('attendance')
            ->count();
    }

    private function sendAttendanceCertificate(Registration $registration, Event $event): bool
    {
        if ($registration->attendance_certificate_sent_at !== null) {
            return false;
        }

        return $this->sendCertificate($registration, $event, 'attendance');
    }

    private function sendParticipationCertificate(Registration $registration, Event $event): bool
    {
        if ($registration->participation_certificate_sent_at !== null) {
            return false;
        }

        return $this->sendCertificate($registration, $event, 'participation');
    }

    private function sendCertificate(Registration $registration, Event $event, string $type): bool
    {
        $recipient = $this->resolveRecipient($registration);
        if ($recipient['email'] === '') {
            return false;
        }

        $certificateLabel = $type === 'participation'
            ? 'Certificate of Participation'
            : 'Certificate of Attendance';

        try {
            Mail::to($recipient['email'])->send(new EventCertificateMail(
                eventName: (string) $event->event_name,
                fullName: $recipient['name'],
                certificateLabel: $certificateLabel,
                eventEndDate: $this->resolveEventEndDate($event)->format('F j, Y'),
            ));

            $timestamp = now();
            $updates = $type === 'participation'
                ? ['participation_certificate_sent_at' => $timestamp]
                : ['attendance_certificate_sent_at' => $timestamp];

            Registration::query()
                ->where('registration_id', $registration->registration_id)
                ->update($updates);

            return true;
        } catch (Throwable $exception) {
            Log::error('Failed to send event certificate.', [
                'registration_id' => $registration->registration_id,
                'event_id' => $event->event_id,
                'certificate_type' => $type,
                'email' => $recipient['email'],
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * @return array{name: string, email: string}
     */
    private function resolveRecipient(Registration $registration): array
    {
        if ($registration->user !== null) {
            $name = trim((string) ($registration->user->firstname ?? '').' '.(string) ($registration->user->lastname ?? ''));

            return [
                'name' => $name !== '' ? $name : 'Participant',
                'email' => strtolower(trim((string) ($registration->user->email ?? ''))),
            ];
        }

        if ($registration->eventRegistrant !== null) {
            $name = trim((string) ($registration->eventRegistrant->first_name ?? '').' '.(string) ($registration->eventRegistrant->last_name ?? ''));

            return [
                'name' => $name !== '' ? $name : 'Participant',
                'email' => strtolower(trim((string) ($registration->eventRegistrant->email ?? ''))),
            ];
        }

        return ['name' => 'Participant', 'email' => ''];
    }

    private function resolveEventEndDate(Event $event): Carbon
    {
        return $event->resolveEndAt() ?? now();
    }
}
