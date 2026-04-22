<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class QrAttendanceService
{
  public function validateAndRecord(string $qrCode): string
  {
    $qrCode = trim($qrCode);

    if ($qrCode === '') {
      return 'Invalid';
    }

    return DB::transaction(function () use ($qrCode): string {
      $digitalIds = DB::table('digital_ids')
        ->select(['digital_id', 'user_id', 'event_id'])
        ->where('qr_code', $qrCode)
        ->limit(2)
        ->get();

      // Ambiguous or missing QR values are treated as invalid.
      if ($digitalIds->count() !== 1) {
        return 'Invalid';
      }

      $digitalId = $digitalIds->first();

      $registration = DB::table('registrations')
        ->where('user_id', $digitalId->user_id)
        ->where('event_id', $digitalId->event_id)
        ->where('status', 'approved')
        ->orderByDesc('registration_id')
        ->lockForUpdate()
        ->first();

      if (! $registration) {
        return 'Invalid';
      }

      $alreadyCheckedIn = DB::table('attendance')
        ->where('registration_id', $registration->registration_id)
        ->whereNotNull('check_in_time')
        ->lockForUpdate()
        ->exists();

      if ($alreadyCheckedIn) {
        return 'Already used';
      }

      $pendingAttendance = DB::table('attendance')
        ->where('registration_id', $registration->registration_id)
        ->whereNull('check_in_time')
        ->orderBy('attendance_id')
        ->lockForUpdate()
        ->first();

      if ($pendingAttendance) {
        DB::table('attendance')
          ->where('attendance_id', $pendingAttendance->attendance_id)
          ->update([
            'check_in_time' => now(),
            'check_out_time' => null,
          ]);

        return 'Valid';
      }

      DB::table('attendance')->insert([
        'registration_id' => $registration->registration_id,
        'check_in_time' => now(),
        'check_out_time' => null,
      ]);

      return 'Valid';
    }, 3);
  }
}
