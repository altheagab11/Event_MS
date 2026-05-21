<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\RegistrationVerificationCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Throwable;

class DigitalPassService
{
    public function __construct(
        private readonly DigitalPassQrService $qrService,
        private readonly DigitalPassAttendanceContextService $attendanceContext,
    ) {}

    /**
     * @return array<string, mixed>|null
     */
    public function buildFromPassCode(string $passCode): ?array
    {
        $digitalId = DB::table('digital_ids')
            ->where('qr_code', $passCode)
            ->first();

        if ($digitalId === null) {
            return null;
        }

        $event = DB::table('events')
            ->where('event_id', $digitalId->event_id)
            ->first();

        if ($event === null) {
            return null;
        }

        $user = $digitalId->user_id !== null
            ? DB::table('users')->where('user_id', $digitalId->user_id)->first()
            : null;

        $registration = Registration::query()
            ->where('event_id', $digitalId->event_id)
            ->when($digitalId->user_id !== null, fn ($query) => $query->where('user_id', $digitalId->user_id))
            ->when(
                $digitalId->user_id === null && isset($digitalId->event_registrant_id) && $digitalId->event_registrant_id !== null,
                fn ($query) => $query->where('event_registrant_id', $digitalId->event_registrant_id)
            )
            ->with('eventRegistrant:event_registrant_id,first_name,last_name,email,school_university,user_type,participant_role')
            ->orderByDesc('registration_date')
            ->first();

        $fullName = $user !== null
            ? trim((string) ($user->firstname ?? '').' '.(string) ($user->lastname ?? ''))
            : trim((string) ($registration?->eventRegistrant?->first_name ?? '').' '.(string) ($registration?->eventRegistrant?->last_name ?? ''));

        $email = (string) ($user->email ?? $registration?->eventRegistrant?->email ?? '');

        return $this->assemblePassData(
            passCode: $passCode,
            event: $event,
            fullName: $fullName !== '' ? $fullName : 'Attendee',
            email: $email,
            registration: $registration,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function buildFromRegistration(Registration $registration, string $passCode): array
    {
        $registration->loadMissing([
            'user',
            'event',
            'eventRegistrant:event_registrant_id,first_name,last_name,email,school_university,user_type,participant_role',
        ]);

        $fullName = $registration->user !== null
            ? trim((string) ($registration->user->firstname ?? '').' '.(string) ($registration->user->lastname ?? ''))
            : trim((string) ($registration->eventRegistrant?->first_name ?? '').' '.(string) ($registration->eventRegistrant?->last_name ?? ''));

        $email = (string) ($registration->user->email ?? $registration->eventRegistrant?->email ?? '');

        return $this->assemblePassData(
            passCode: $passCode,
            event: $registration->event,
            fullName: $fullName !== '' ? $fullName : 'Attendee',
            email: $email,
            registration: $registration,
        );
    }

    public function signedViewUrl(string $passCode): string
    {
        return URL::temporarySignedRoute(
            'digital-pass.show',
            now()->addYear(),
            ['pass' => $passCode]
        );
    }

    public function signedDownloadUrl(string $passCode): string
    {
        return URL::temporarySignedRoute(
            'digital-pass.download',
            now()->addYear(),
            ['pass' => $passCode]
        );
    }

    /**
     * @param  object  $event
     * @return array<string, mixed>
     */
    private function assemblePassData(
        string $passCode,
        object $event,
        string $fullName,
        string $email,
        ?Registration $registration = null,
    ): array {
        $attendance = $this->attendanceContext->resolve($event, $registration);

        return [
            'event_name' => (string) ($event->event_name ?? 'Event'),
            'event_date' => $this->formatEventDate($event->event_date ?? null),
            'event_start' => $this->formatEventDateTime($event->start_date ?? $event->event_date ?? null),
            'event_end' => $this->formatEventDateTime($event->end_date ?? $event->start_date ?? $event->event_date ?? null),
            'location' => (string) ($event->location ?? 'TBA'),
            'attendance_format' => trim((string) ($event->attendance_format ?? 'Face-to-Face')),
            'full_name' => $fullName,
            'email' => $email,
            'profile_line' => $this->buildProfileLine($registration, $email, (int) ($event->event_id ?? 0)),
            'valid_thru' => $this->formatValidThru($event),
            'pass_code' => $passCode,
            'attendance_mode' => $attendance['attendance_mode'],
            'attendance_mode_label' => $attendance['attendance_mode_label'],
            'checkin_method_label' => $attendance['checkin_method_label'],
            'qr_purpose' => $attendance['qr_purpose'],
            'qr_scan_hint' => $attendance['qr_scan_hint'],
            'uses_venue_qr_scan' => $attendance['uses_venue_qr_scan'],
            'online_attendance_url' => $attendance['online_attendance_url'],
            'qr_code' => $passCode,
            'qr_embed' => $this->qrService->forEmailEmbed($passCode),
            'qr_svg' => $this->qrService->asSvgString($passCode, 220),
            'view_url' => $this->signedViewUrl($passCode),
            'download_url' => $this->signedDownloadUrl($passCode),
        ];
    }

    private function buildProfileLine(?Registration $registration, string $email, int $eventId): string
    {
        $parts = [];
        $registrant = $registration?->eventRegistrant;
        $payload = [];

        if ($registrant !== null) {
            $this->pushProfilePart($parts, (string) $registrant->user_type);
            $this->pushProfilePart($parts, (string) $registrant->participant_role);
            $this->pushProfilePart($parts, (string) $registrant->school_university);
        } elseif ($email !== '' && $eventId > 0) {
            $verification = RegistrationVerificationCode::query()
                ->where('event_id', $eventId)
                ->whereRaw('LOWER(email) = ?', [strtolower($email)])
                ->orderByDesc('id')
                ->first();

            $payload = (array) ($verification?->payload ?? []);
            $this->pushProfilePart($parts, (string) ($payload['user_type'] ?? $payload['school_from'] ?? ''));
            $this->pushProfilePart($parts, (string) ($payload['participant_role'] ?? $payload['school_level'] ?? ''));
            $this->pushProfilePart($parts, (string) ($payload['school_university'] ?? $payload['region'] ?? $payload['school_affiliation'] ?? ''));
        }

        return $parts !== [] ? implode(' • ', $parts) : 'Event Participant';
    }

    /**
     * @param  list<string>  $parts
     */
    private function pushProfilePart(array &$parts, string $value): void
    {
        $value = trim($value);

        if ($value === '') {
            return;
        }

        foreach ($parts as $existing) {
            if (strcasecmp($existing, $value) === 0) {
                return;
            }
        }

        $parts[] = $value;
    }

    /**
     * @param  object  $event
     */
    private function formatValidThru(object $event): string
    {
        $startRaw = $event->start_date ?? $event->event_date ?? null;
        $endRaw = $event->end_date ?? $startRaw ?? null;

        if ($startRaw === null || (string) $startRaw === '') {
            return 'TBA';
        }

        try {
            $start = Carbon::parse((string) $startRaw)->startOfDay();
            $end = Carbon::parse((string) ($endRaw ?? $startRaw))->startOfDay();
        } catch (Throwable) {
            return 'TBA';
        }

        $startLabel = $start->format('j/y');

        if ($end->lte($start)) {
            return $startLabel;
        }

        $daySpan = $start->diffInDays($end) + 1;

        if ($daySpan <= 1) {
            return $startLabel;
        }

        return $startLabel.'-'.$end->format('j/y');
    }

    private function formatEventDate(mixed $eventDate): string
    {
        if ($eventDate === null || (string) $eventDate === '') {
            return 'TBA';
        }

        try {
            return Carbon::parse((string) $eventDate)->format('F j, Y');
        } catch (Throwable) {
            return (string) $eventDate;
        }
    }

    private function formatEventDateTime(mixed $eventDateTime): string
    {
        if ($eventDateTime === null || (string) $eventDateTime === '') {
            return 'TBA';
        }

        try {
            return Carbon::parse((string) $eventDateTime)->format('M j, Y • g:i A');
        } catch (Throwable) {
            return (string) $eventDateTime;
        }
    }
}
