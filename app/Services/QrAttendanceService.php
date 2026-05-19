<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class QrAttendanceService
{
    /**
     * @return array<string, mixed>
     */
    public function validateAndRecord(string $qrCode): array
    {
        $qrCode = trim($qrCode);

        if ($qrCode === '') {
            return $this->invalid('Invalid QR Code.');
        }

        return DB::transaction(function () use ($qrCode): array {
            $digitalIds = DB::table('digital_ids')
                ->select(['digital_id', 'event_registrant_id', 'event_id'])
                ->where('qr_code', $qrCode)
                ->limit(2)
                ->get();

            if ($digitalIds->count() !== 1) {
                return $this->invalid('Invalid QR Code.');
            }

            $digitalId = $digitalIds->first();

            if ($digitalId->event_registrant_id === null) {
                return $this->invalid('Invalid QR Code.');
            }

            $registrant = DB::table('event_registrants')
                ->where('event_registrant_id', $digitalId->event_registrant_id)
                ->lockForUpdate()
                ->first();

            if ($registrant === null) {
                return $this->invalid('Invalid QR Code.');
            }

            if ((int) $registrant->event_id !== (int) $digitalId->event_id) {
                return $this->invalid('Invalid QR Code.');
            }

            $participantStatus = strtolower((string) $registrant->status);
            if (! in_array($participantStatus, ['approved', 'registered'], true)) {
                return $this->invalid('Participant is not approved for check-in.');
            }

            $event = DB::table('events')
                ->where('event_id', $digitalId->event_id)
                ->first();

            if ($event === null) {
                return $this->invalid('Invalid QR Code.');
            }

            $eventRegistrantId = (int) $digitalId->event_registrant_id;
            $eventId = (int) $digitalId->event_id;

            $session = DB::table('event_sessions')
                ->where('event_id', $eventId)
                ->whereDate('session_date', now()->toDateString())
                ->lockForUpdate()
                ->first();

            if ($session === null) {
                return $this->invalid('No event session is scheduled for today. Check the event schedule.');
            }

            $sessionStart = $this->resolveSessionStartAt($session);
            if ($sessionStart !== null && now()->lt($sessionStart)) {
                return $this->invalid(
                    'Check-in is not available yet. This event starts at '.$sessionStart->format('M j, Y g:i A').'.'
                );
            }

            $sessionId = (int) $session->session_id;

            $existingAttendance = DB::table('attendance')
                ->where('registration_id', $eventRegistrantId)
                ->where('session_id', $sessionId)
                ->lockForUpdate()
                ->first();

            if ($existingAttendance !== null) {
                return [
                    'status' => 'duplicate',
                    'message' => 'Participant already checked in for '.$session->session_label.'.',
                ];
            }

            $checkInTime = now();

            DB::table('attendance')->insert([
                'registration_id' => $eventRegistrantId,
                'session_id' => $sessionId,
                'check_in_time' => $checkInTime,
                'check_out_time' => null,
            ]);

            $name = trim((string) $registrant->first_name.' '.(string) $registrant->last_name);
            $formattedCheckIn = $checkInTime instanceof Carbon
                ? $checkInTime->format('M j, Y g:i A')
                : Carbon::parse((string) $checkInTime)->format('M j, Y g:i A');

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
                    'attendance_status' => 'Attended',
                ],
            ];
        }, 3);
    }

    private function resolveSessionStartAt(object $session): ?Carbon
    {
        $sessionDate = $session->session_date ?? null;
        if ($sessionDate === null) {
            return null;
        }

        $time = (string) ($session->start_time ?? '00:00:00');
        if ($time === '') {
            $time = '00:00:00';
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
    private function invalid(string $message): array
    {
        return [
            'status' => 'invalid',
            'message' => $message,
        ];
    }
}
