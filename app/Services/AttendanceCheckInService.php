<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AttendanceCheckInService
{
    public const STATUS_PRESENT = 'Present';

    public const STATUS_ABSENT = 'Absent';

    public const METHOD_QR_SCANNER = 'QR Scanner';

    public const METHOD_ONLINE_LINK = 'Online Link';

    public const DUPLICATE_MESSAGE = 'Attendance has already been recorded.';

    public function __construct(
        private readonly EventOnlineAttendanceTokenService $onlineTokenService,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function recordQrCheckIn(int $eventRegistrantId, int $eventId): array
    {
        return DB::transaction(function () use ($eventRegistrantId, $eventId): array {
            $registrant = DB::table('event_registrants')
                ->where('event_registrant_id', $eventRegistrantId)
                ->lockForUpdate()
                ->first();

            if ($registrant === null) {
                return $this->invalid('Invalid QR Code.');
            }

            if ((int) $registrant->event_id !== $eventId) {
                return $this->invalid('Invalid QR Code.');
            }

            $event = Event::query()->where('event_id', $eventId)->first();
            if ($event === null) {
                return $this->invalid('Invalid QR Code.');
            }

            $formatError = $this->validatePhysicalCheckInAllowed($event, $registrant);
            if ($formatError !== null) {
                return $this->invalid($formatError);
            }

            $registration = $this->resolveRegistration($eventRegistrantId, $eventId);
            if ($registration !== null) {
                $duplicate = $this->duplicateResponse($registration, $eventRegistrantId);
                if ($duplicate !== null) {
                    return $duplicate;
                }
            } elseif ($this->hasSessionAttendance($eventRegistrantId)) {
                return $this->duplicate();
            }

            $participantStatus = strtolower((string) $registrant->status);
            if (! in_array($participantStatus, ['approved', 'registered'], true)) {
                return $this->invalid('Participant is not approved for check-in.');
            }

            $session = $this->resolveTodaySession($eventId);
            if ($session === null) {
                return $this->invalid('No event session is scheduled for today. Check the event schedule.');
            }

            $sessionEnd = $this->resolveSessionEndAt($session);
            if ($sessionEnd !== null && now()->gt($sessionEnd)) {
                return $this->invalid(
                    'Check-in is closed for today. This session ended at '.$sessionEnd->format('M j, Y g:i A').'.'
                );
            }

            $checkInTime = now();
            $sessionId = (int) $session->session_id;

            if (! $this->hasSessionAttendanceForSession($eventRegistrantId, $sessionId)) {
                DB::table('attendance')->insert([
                    'registration_id' => $eventRegistrantId,
                    'session_id' => $sessionId,
                    'check_in_time' => $checkInTime,
                    'check_out_time' => null,
                ]);
            }

            $recordedMode = $this->resolvePhysicalAttendanceMode($event, $registration);
            $this->updateRegistrationAttendance($registration, $eventRegistrantId, $eventId, [
                'attendance_mode' => $recordedMode,
                'attendance_status' => self::STATUS_PRESENT,
                'checkin_method' => self::METHOD_QR_SCANNER,
                'checked_in_at' => $checkInTime,
            ]);

            return $this->successResponse($registrant, $event, $session, $checkInTime, $recordedMode);
        }, 3);
    }

    /**
     * @return array<string, mixed>
     */
    public function recordOnlineCheckIn(Registration $registration, Event $event): array
    {
        return DB::transaction(function () use ($registration, $event): array {
            $registration = Registration::query()
                ->where('registration_id', $registration->registration_id)
                ->lockForUpdate()
                ->first();

            if ($registration === null) {
                return $this->invalid('Registration not found.');
            }

            $registrant = null;
            if ($registration->event_registrant_id !== null) {
                $registrant = DB::table('event_registrants')
                    ->where('event_registrant_id', $registration->event_registrant_id)
                    ->lockForUpdate()
                    ->first();
            }

            $formatError = $this->validateOnlineCheckInAllowed($event, $registration, $registrant);
            if ($formatError !== null) {
                return $this->invalid($formatError);
            }

            $duplicate = $this->duplicateResponse(
                $registration,
                (int) ($registration->event_registrant_id ?? 0)
            );
            if ($duplicate !== null) {
                return $duplicate;
            }

            $status = strtolower((string) ($registrant->status ?? $registration->status ?? ''));
            if (! in_array($status, ['approved', 'registered'], true)) {
                return $this->invalid('Your registration must be approved before you can check in.');
            }

            $eventRegistrantId = (int) $registration->event_registrant_id;
            $session = $this->resolveTodaySession((int) $event->event_id);
            if ($session === null) {
                return $this->invalid('Online check-in is not open yet. There is no session scheduled for today.');
            }

            $sessionEnd = $this->resolveSessionEndAt($session);
            if ($sessionEnd !== null && now()->gt($sessionEnd)) {
                return $this->invalid(
                    'Online check-in is closed for today. This session ended at '.$sessionEnd->format('M j, Y g:i A').'.'
                );
            }

            $checkInTime = now();
            $sessionId = (int) $session->session_id;

            if ($eventRegistrantId > 0 && ! $this->hasSessionAttendanceForSession($eventRegistrantId, $sessionId)) {
                DB::table('attendance')->insert([
                    'registration_id' => $eventRegistrantId,
                    'session_id' => $sessionId,
                    'check_in_time' => $checkInTime,
                    'check_out_time' => null,
                ]);
            }

            $this->updateRegistrationAttendance($registration, $eventRegistrantId, (int) $event->event_id, [
                'attendance_mode' => 'Online',
                'attendance_status' => self::STATUS_PRESENT,
                'checkin_method' => self::METHOD_ONLINE_LINK,
                'checked_in_at' => $checkInTime,
            ]);

            $name = $this->resolveParticipantName($registration, $registrant);

            return [
                'status' => 'success',
                'message' => 'Your attendance has been recorded. Thank you for checking in!',
                'participant_name' => $name,
                'event_name' => (string) $event->event_name,
                'check_in_time' => $checkInTime->format('M j, Y g:i A'),
                'attendance_mode' => 'Online',
            ];
        }, 3);
    }

    public function isCheckedIn(Registration $registration): bool
    {
        if (Schema::hasColumn('registrations', 'attendance_status')
            && strcasecmp((string) ($registration->attendance_status ?? ''), self::STATUS_PRESENT) === 0) {
            return true;
        }

        if ($registration->event_registrant_id === null) {
            return false;
        }

        return $this->hasSessionAttendance((int) $registration->event_registrant_id);
    }

    /**
     * @return array{status: string, message: string}|null
     */
    private function duplicateResponse(Registration $registration, int $eventRegistrantId): ?array
    {
        if ($this->isCheckedIn($registration)) {
            return $this->duplicate();
        }

        if ($eventRegistrantId > 0 && $this->hasSessionAttendance($eventRegistrantId)) {
            return $this->duplicate();
        }

        return null;
    }

    private function validatePhysicalCheckInAllowed(Event $event, object $registrant): ?string
    {
        $format = trim((string) $event->attendance_format);

        if ($format === 'Online') {
            return 'This is an online event. Please use the online attendance link shared during the meeting.';
        }

        if ($format === 'Hybrid') {
            $registration = $this->resolveRegistration((int) $registrant->event_registrant_id, (int) $event->event_id);
            $mode = strtolower(trim((string) ($registration->attendance_mode ?? '')));
            if ($mode === 'online') {
                return 'You registered for online attendance. Please use the online attendance link shared during the meeting.';
            }
        }

        return null;
    }

    private function validateOnlineCheckInAllowed(Event $event, Registration $registration, ?object $registrant): ?string
    {
        $format = trim((string) $event->attendance_format);

        if ($format === 'Face-to-Face') {
            return 'This event uses on-site QR check-in. Please scan your participant pass at the venue.';
        }

        if ($format === 'Hybrid') {
            $mode = strtolower(trim((string) ($registration->attendance_mode ?? '')));
            if ($mode === 'face-to-face') {
                return 'You registered for face-to-face attendance. Please check in using your participant QR code at the venue.';
            }
        }

        if (! $this->onlineTokenService->supportsOnlineAttendance($event)) {
            return 'Online attendance is not available for this event.';
        }

        return null;
    }

    private function resolvePhysicalAttendanceMode(Event $event, ?Registration $registration): string
    {
        $format = trim((string) $event->attendance_format);
        if ($format === 'Online') {
            return 'Online';
        }

        if ($format === 'Hybrid') {
            $selected = trim((string) ($registration->attendance_mode ?? ''));

            return $selected !== '' ? $selected : 'Face-to-Face';
        }

        return 'Face-to-Face';
    }

    private function resolveRegistration(int $eventRegistrantId, int $eventId): ?Registration
    {
        return Registration::query()
            ->where('event_registrant_id', $eventRegistrantId)
            ->where('event_id', $eventId)
            ->orderByDesc('registration_id')
            ->first();
    }

    /**
     * @param  array<string, mixed>  $values
     */
    private function updateRegistrationAttendance(
        ?Registration $registration,
        int $eventRegistrantId,
        int $eventId,
        array $values,
    ): void {
        if ($registration === null) {
            $registration = $this->resolveRegistration($eventRegistrantId, $eventId);
        }

        if ($registration === null) {
            return;
        }

        $update = [];
        foreach (['attendance_mode', 'attendance_status', 'checkin_method', 'checked_in_at'] as $column) {
            if (! Schema::hasColumn('registrations', $column) || ! array_key_exists($column, $values)) {
                continue;
            }
            $update[$column] = $values[$column];
        }

        if ($update !== []) {
            Registration::query()
                ->where('registration_id', $registration->registration_id)
                ->update($update);
        }
    }

    private function hasSessionAttendance(int $eventRegistrantId): bool
    {
        return DB::table('attendance')
            ->where('registration_id', $eventRegistrantId)
            ->exists();
    }

    private function hasSessionAttendanceForSession(int $eventRegistrantId, int $sessionId): bool
    {
        return DB::table('attendance')
            ->where('registration_id', $eventRegistrantId)
            ->where('session_id', $sessionId)
            ->exists();
    }

    private function resolveTodaySession(int $eventId): ?object
    {
        return DB::table('event_sessions')
            ->where('event_id', $eventId)
            ->whereDate('session_date', now()->toDateString())
            ->lockForUpdate()
            ->first();
    }

    /**
     * @return array<string, mixed>
     */
    private function successResponse(object $registrant, Event $event, object $session, Carbon $checkInTime, string $attendanceMode): array
    {
        $name = trim((string) $registrant->first_name.' '.(string) $registrant->last_name);
        $formattedCheckIn = $checkInTime->format('M j, Y g:i A');

        return [
            'status' => 'success',
            'message' => $name !== ''
                ? "Check-in recorded for {$name}."
                : 'Check-in recorded successfully.',
            'participant' => [
                'name' => $name !== '' ? $name : 'Participant',
                'email' => (string) ($registrant->email ?? ''),
                'event_name' => (string) ($event->event_name ?? ''),
                'event_type' => $this->formatEventTypeLabel((string) ($event->event_type ?? '')),
                'session_label' => (string) ($session->session_label ?? ''),
                'check_in_time' => $formattedCheckIn,
                'attendance_status' => self::STATUS_PRESENT,
                'attendance_mode' => $attendanceMode,
            ],
        ];
    }

    private function resolveParticipantName(Registration $registration, ?object $registrant): string
    {
        if ($registrant !== null) {
            $name = trim((string) $registrant->first_name.' '.(string) $registrant->last_name);
            if ($name !== '') {
                return $name;
            }
        }

        $registration->loadMissing('eventRegistrant', 'user');
        if ($registration->eventRegistrant !== null) {
            $name = trim((string) $registration->eventRegistrant->first_name.' '.$registration->eventRegistrant->last_name);
            if ($name !== '') {
                return $name;
            }
        }

        if ($registration->user !== null) {
            $name = trim((string) $registration->user->firstname.' '.$registration->user->lastname);
            if ($name !== '') {
                return $name;
            }
        }

        return 'Participant';
    }

    private function resolveSessionEndAt(object $session): ?Carbon
    {
        $sessionDate = $session->session_date ?? null;
        if ($sessionDate === null) {
            return null;
        }

        $time = (string) ($session->end_time ?? '23:59:59');
        if ($time === '') {
            $time = '23:59:59';
        }

        return Carbon::parse(Carbon::parse((string) $sessionDate)->format('Y-m-d').' '.$time);
    }

    private function formatEventTypeLabel(string $eventType): string
    {
        return str_contains(strtolower($eventType), 'conference')
            ? 'Conference Event'
            : 'School Event';
    }

    /**
     * @return array{status: string, message: string}
     */
    private function duplicate(): array
    {
        return [
            'status' => 'duplicate',
            'message' => self::DUPLICATE_MESSAGE,
        ];
    }

    /**
     * @return array{status: string, message: string}
     */
    private function invalid(string $message): array
    {
        return [
            'status' => 'invalid',
            'message' => $message,
        ];
    }
}
