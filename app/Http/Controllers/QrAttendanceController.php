<?php

namespace App\Http\Controllers;

use App\Services\QrAttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class QrAttendanceController extends Controller
{
    public function validateAndCheckIn(Request $request, QrAttendanceService $qrAttendanceService): JsonResponse
    {
        try {
            $result = $qrAttendanceService->validateAndRecord((string) $request->input('qr_code', ''));
        } catch (Throwable) {
            $result = [
                'status' => 'invalid',
                'message' => 'Unable to process QR code. Please try again.',
            ];
        }

        $httpStatus = match ($result['status']) {
            'success' => 200,
            'duplicate' => 409,
            default => 422,
        };

        return response()->json($result, $httpStatus);
    }
}
