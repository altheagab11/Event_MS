<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitOnlineAttendanceRequest;
use App\Services\OnlineAttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ParticipantOnlineAttendanceController extends Controller
{
    public function show(string $token, OnlineAttendanceService $onlineAttendanceService): View
    {
        $context = $onlineAttendanceService->resolveEventContext($token);

        if (! ($context['valid'] ?? false)) {
            return view('attendance.invalid', [
                'message' => $onlineAttendanceService->errorMessages()[(string) ($context['error'] ?? '')]
                    ?? 'This online attendance link is not available.',
            ]);
        }

        $event = $context['event'];

        return view('attendance.checkin', [
            'token' => $token,
            'eventName' => (string) $event->event_name,
            'eventDate' => $event->formatted_schedule_range,
            'submitUrl' => route('events.online-attendance.store', ['token' => $token]),
        ]);
    }

    public function store(
        SubmitOnlineAttendanceRequest $request,
        string $token,
        OnlineAttendanceService $onlineAttendanceService,
    ): RedirectResponse|View {
        $result = $onlineAttendanceService->checkInByEmail($token, (string) $request->input('email'));

        $status = (string) ($result['status'] ?? 'invalid');

        if ($status === 'success') {
            return view('attendance.success', [
                'participantName' => (string) ($result['participant_name'] ?? 'Participant'),
                'eventName' => (string) ($result['event_name'] ?? 'the event'),
                'checkInTime' => (string) ($result['check_in_time'] ?? ''),
                'attendanceMode' => (string) ($result['attendance_mode'] ?? 'Online'),
            ]);
        }

        if ($status === 'duplicate') {
            return view('attendance.invalid', [
                'message' => (string) ($result['message'] ?? 'Attendance has already been recorded.'),
            ]);
        }

        return redirect()
            ->route('events.online-attendance.show', ['token' => $token])
            ->withInput()
            ->withErrors([
                'email' => (string) ($result['message']
                    ?? $onlineAttendanceService->errorMessages()[(string) ($result['error'] ?? '')]
                    ?? 'Unable to record attendance.'),
            ]);
    }
}
