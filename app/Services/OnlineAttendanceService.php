<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OnlineAttendanceService
{
    public const ERROR_INVALID_TOKEN = 'invalid_token';

    public const ERROR_NOT_REGISTERED = 'not_registered';

    public const ERROR_NOT_APPROVED = 'not_approved';

    public const ERROR_WRONG_MODE = 'wrong_mode';

    public const ERROR_NOT_OPEN = 'not_open';

    public function __construct(
        private readonly AttendanceCheckInService $checkInService,
        private readonly EventOnlineAttendanceTokenService $tokenService,
        private readonly DigitalPassQrService $qrService,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function resolveEventContext(string $token): array
    {
        $token = trim($token);
        if ($token === '' || ! Schema::hasColumn('events', 'online_attendance_token')) {
            return ['valid' => false, 'error' => self::ERROR_INVALID_TOKEN];
        }

        $event = Event::query()
            ->where('online_attendance_token', $token)
            ->first();

        if ($event === null) {
            return ['valid' => false, 'error' => self::ERROR_INVALID_TOKEN];
        }

        if (! $this->tokenService->supportsOnlineAttendance($event)) {
            return ['valid' => false, 'error' => self::ERROR_NOT_OPEN];
        }

        $this->tokenService->ensureToken($event);

        return [
            'valid' => true,
            'event' => $event,
            'check_in_url' => route('events.online-attendance.show', ['token' => $token]),
            'qr_svg' => $this->qrService->asSvgString(route('events.online-attendance.show', ['token' => $token]), 200),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function checkInByEmail(string $token, string $email): array
    {
        $context = $this->resolveEventContext($token);
        if (! ($context['valid'] ?? false)) {
            return [
                'status' => 'invalid',
                'error' => (string) ($context['error'] ?? self::ERROR_INVALID_TOKEN),
            ];
        }

        /** @var Event $event */
        $event = $context['event'];
        $email = strtolower(trim($email));

        if ($email === '') {
            return [
                'status' => 'invalid',
                'message' => 'Please enter the email address you used to register.',
            ];
        }

        $registration = $this->findRegistrationByEmail($event, $email);
        if ($registration === null) {
            return [
                'status' => 'invalid',
                'error' => self::ERROR_NOT_REGISTERED,
                'message' => 'No approved registration was found for this email address.',
            ];
        }

        $result = $this->checkInService->recordOnlineCheckIn($registration, $event);
        $status = (string) ($result['status'] ?? 'invalid');

        if ($status === 'duplicate') {
            return [
                'status' => 'duplicate',
                'message' => (string) ($result['message'] ?? AttendanceCheckInService::DUPLICATE_MESSAGE),
            ];
        }

        if ($status !== 'success') {
            return [
                'status' => 'invalid',
                'message' => (string) ($result['message'] ?? 'Unable to record attendance.'),
            ];
        }

        return $result;
    }

    /**
     * @return array<string, string>
     */
    public function errorMessages(): array
    {
        return [
            self::ERROR_INVALID_TOKEN => 'This online attendance link is invalid or has expired.',
            self::ERROR_NOT_REGISTERED => 'No approved registration was found for this email address.',
            self::ERROR_NOT_APPROVED => 'Your registration must be approved before you can check in.',
            self::ERROR_WRONG_MODE => 'Your registration is not set for online attendance.',
            self::ERROR_NOT_OPEN => 'Online attendance is not available for this event.',
        ];
    }

    private function findRegistrationByEmail(Event $event, string $email): ?Registration
    {
        $registrant = DB::table('event_registrants')
            ->where('event_id', $event->event_id)
            ->whereRaw('LOWER(email) = ?', [$email])
            ->whereIn('status', ['approved', 'registered'])
            ->first();

        if ($registrant !== null) {
            return Registration::query()
                ->where('event_id', $event->event_id)
                ->where('event_registrant_id', $registrant->event_registrant_id)
                ->orderByDesc('registration_id')
                ->first();
        }

        return Registration::query()
            ->join('users', 'users.id', '=', 'registrations.user_id')
            ->where('registrations.event_id', $event->event_id)
            ->whereRaw('LOWER(users.email) = ?', [$email])
            ->whereIn('registrations.status', ['approved', 'registered'])
            ->select('registrations.*')
            ->orderByDesc('registrations.registration_id')
            ->first();
    }
}
