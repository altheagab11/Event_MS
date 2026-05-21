<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class QrAttendanceService
{
    public function __construct(
        private readonly AttendanceCheckInService $checkInService,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function validateAndRecord(string $qrCode): array
    {
        $qrCode = trim($qrCode);

        if ($qrCode === '') {
            return $this->invalid('Invalid QR Code.');
        }

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

        return $this->checkInService->recordQrCheckIn(
            (int) $digitalId->event_registrant_id,
            (int) $digitalId->event_id,
        );
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
