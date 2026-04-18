<?php

namespace App\Http\Controllers;

use App\Services\QrAttendanceService;
use Illuminate\Http\Request;
use Throwable;

class QrAttendanceController extends Controller
{
  public function validateAndCheckIn(Request $request, QrAttendanceService $qrAttendanceService)
  {
    try {
      $status = $qrAttendanceService->validateAndRecord((string) $request->input('qr_code', ''));
    } catch (Throwable) {
      $status = 'Invalid';
    }

    return response($status, 200)->header('Content-Type', 'text/plain');
  }
}
