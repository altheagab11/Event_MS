<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Registration;

class DigitalPassAttendanceContextService
{
    public const PURPOSE_VENUE_SCAN = 'venue_scan';

    public const PURPOSE_ONLINE_ATTENDANCE = 'online_attendance';

    public function __construct(
        private readonly EventOnlineAttendanceTokenService $onlineTokenService,
    ) {}

    /**
     * @param  object|Event  $event
     * @return array{
     *   attendance_mode: string,
     *   attendance_mode_label: string,
     *   checkin_method_label: string,
     *   qr_purpose: string,
     *   qr_scan_hint: string,
     *   uses_venue_qr_scan: bool,
     *   online_attendance_url: ?string
     * }
     */
    public function resolve(object $event, ?Registration $registration = null): array
    {
        $attendanceMode = $this->resolveAttendanceMode($event, $registration);
        $usesVenueQrScan = strcasecmp($attendanceMode, 'Face-to-Face') === 0;

        $onlineAttendanceUrl = null;
        if (! $usesVenueQrScan) {
            $onlineAttendanceUrl = $this->resolveOnlineAttendanceUrl($event);
        }

        return [
            'attendance_mode' => $attendanceMode,
            'attendance_mode_label' => $attendanceMode,
            'checkin_method_label' => $usesVenueQrScan ? 'Venue QR Scan' : 'Online Attendance Link',
            'qr_purpose' => $usesVenueQrScan ? self::PURPOSE_VENUE_SCAN : self::PURPOSE_ONLINE_ATTENDANCE,
            'qr_scan_hint' => $usesVenueQrScan ? 'Scan at venue' : 'Online check-in',
            'uses_venue_qr_scan' => $usesVenueQrScan,
            'online_attendance_url' => $onlineAttendanceUrl,
        ];
    }

    /**
     * @param  object|Event  $event
     */
    public function resolveQrContent(object $event, ?Registration $registration, string $passCode): string
    {
        $context = $this->resolve($event, $registration);

        if ($context['uses_venue_qr_scan']) {
            return $passCode;
        }

        return (string) ($context['online_attendance_url'] ?? $passCode);
    }

    /**
     * @param  object|Event  $event
     */
    private function resolveAttendanceMode(object $event, ?Registration $registration): string
    {
        $format = trim((string) ($event->attendance_format ?? 'Face-to-Face'));

        if ($format === 'Online') {
            return 'Online';
        }

        if ($format === 'Hybrid') {
            $selected = trim((string) ($registration->attendance_mode ?? ''));

            return $selected !== '' ? $selected : 'Face-to-Face';
        }

        return 'Face-to-Face';
    }

    /**
     * @param  object|Event  $event
     */
    private function resolveOnlineAttendanceUrl(object $event): ?string
    {
        $eventId = (int) ($event->event_id ?? 0);
        if ($eventId <= 0) {
            return null;
        }

        $eventModel = $event instanceof Event
            ? $event
            : Event::query()->find($eventId);

        if ($eventModel === null || ! $this->onlineTokenService->supportsOnlineAttendance($eventModel)) {
            return null;
        }

        return $this->onlineTokenService->checkInUrl($eventModel);
    }
}
