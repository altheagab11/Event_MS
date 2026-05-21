<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\EventSessionSyncService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EventAttendanceController extends Controller
{
    public function __construct(
        private readonly EventSessionSyncService $eventSessionSync,
    ) {}

    public function modal(Event $event): View
    {
        $this->eventSessionSync->syncForEvent($event);

        $event->syncStatusIfEnded();
        $event->applyComputedStatusAttributes();

        $sessions = DB::table('event_sessions')
            ->where('event_id', $event->event_id)
            ->orderBy('session_date')
            ->get(['session_id', 'session_label', 'session_date']);

        $rows = DB::table('event_registrants as er')
            ->where('er.event_id', $event->event_id)
            ->leftJoin('registrations as r', function ($join) use ($event) {
                $join->on('r.event_registrant_id', '=', 'er.event_registrant_id')
                    ->where('r.event_id', '=', $event->event_id);
            })
            ->select([
                'er.event_registrant_id',
                'er.first_name',
                'er.last_name',
                'er.email',
                'er.user_type',
                'er.participant_role',
                'er.status as registrant_status',
                'r.registration_id',
                'r.status as registration_status',
            ])
            ->orderByDesc('er.registration_date')
            ->get();

        $registrantIds = $rows->pluck('event_registrant_id')->map(fn ($id) => (int) $id)->all();

        $attendanceRows = collect();
        if ($registrantIds !== []) {
            $attendanceRows = DB::table('attendance')
                ->whereIn('registration_id', $registrantIds)
                ->get(['registration_id', 'session_id', 'check_in_time']);
        }

        $attendanceMap = [];
        foreach ($attendanceRows as $attendanceRow) {
            $registrantId = (int) $attendanceRow->registration_id;
            $sessionId = (int) $attendanceRow->session_id;
            $attendanceMap[$registrantId][$sessionId] = Carbon::parse((string) $attendanceRow->check_in_time);
        }

        $participants = $rows->map(function ($row) use ($sessions, $attendanceMap): array {
            $name = trim((string) $row->first_name.' '.(string) $row->last_name);
            $registrationStatus = (string) ($row->registrant_status ?: $row->registration_status ?: 'pending');
            $registrantId = (int) $row->event_registrant_id;
            $registrantAttendance = $attendanceMap[$registrantId] ?? [];

            $sessionAttendance = [];
            $hasAnyAttendance = false;
            $latestCheckIn = null;

            foreach ($sessions as $session) {
                $sessionId = (int) $session->session_id;
                $checkIn = $registrantAttendance[$sessionId] ?? null;
                $hasAttended = $checkIn !== null;

                if ($hasAttended) {
                    $hasAnyAttendance = true;
                    if ($latestCheckIn === null || $checkIn->gt($latestCheckIn)) {
                        $latestCheckIn = $checkIn;
                    }
                }

                $sessionAttendance[] = [
                    'session_id' => $sessionId,
                    'attendance_status' => $hasAttended ? 'attended' : 'not_attended',
                    'attendance_status_label' => $hasAttended ? 'Attended' : 'Not Yet Attended',
                    'check_in_time' => $checkIn?->format('M j, Y g:i A'),
                ];
            }

            return [
                'event_registrant_id' => $registrantId,
                'registration_id' => $row->registration_id !== null ? (int) $row->registration_id : null,
                'name' => $name !== '' ? $name : 'Participant',
                'email' => (string) ($row->email ?? ''),
                'user_type' => trim((string) ($row->user_type ?? '')) ?: '—',
                'role' => trim((string) ($row->participant_role ?? '')) ?: '—',
                'registration_status' => $registrationStatus,
                'registration_status_label' => ucfirst($registrationStatus),
                'session_attendance' => $sessionAttendance,
                'attendance_status' => $hasAnyAttendance ? 'attended' : 'not_attended',
                'attendance_status_label' => $hasAnyAttendance ? 'Attended' : 'Not Yet Attended',
                'check_in_time' => $latestCheckIn?->format('M j, Y g:i A'),
                'search_text' => strtolower(trim(implode(' ', [
                    $name,
                    (string) ($row->email ?? ''),
                ]))),
            ];
        });

        $totalRegistered = $participants->count();

        $summaryBySession = [
            'all' => [
                'registered' => $totalRegistered,
                'attended' => $participants->where('attendance_status', 'attended')->count(),
                'not_attended' => $totalRegistered - $participants->where('attendance_status', 'attended')->count(),
            ],
        ];

        foreach ($sessions as $session) {
            $sessionId = (int) $session->session_id;
            $attendedCount = $participants->filter(function (array $participant) use ($sessionId): bool {
                foreach ($participant['session_attendance'] as $sessionRow) {
                    if ((int) $sessionRow['session_id'] === $sessionId) {
                        return $sessionRow['attendance_status'] === 'attended';
                    }
                }

                return false;
            })->count();

            $summaryBySession[(string) $sessionId] = [
                'registered' => $totalRegistered,
                'attended' => $attendedCount,
                'not_attended' => $totalRegistered - $attendedCount,
            ];
        }

        $eventTypeRaw = strtolower((string) ($event->event_type ?? ''));
        $eventTypeLabel = str_contains($eventTypeRaw, 'conference')
            ? 'Conference Event'
            : 'School Event';

        $statusKey = strtolower((string) ($event->computed_status ?? $event->status ?? 'active'));
        $statusLabel = (string) ($event->computed_status_label ?? ucfirst($statusKey));

        $startDate = $event->start_date ?? $event->event_date;
        $endDate = $event->end_date ?? $startDate;

        if ($startDate !== null && ! $startDate instanceof Carbon) {
            $startDate = Carbon::parse((string) $startDate);
        }
        if ($endDate !== null && ! $endDate instanceof Carbon) {
            $endDate = Carbon::parse((string) $endDate);
        }

        $formattedStart = $startDate instanceof Carbon
            ? $startDate->format('F j, Y g:i A')
            : 'TBA';
        $formattedEnd = $endDate instanceof Carbon
            ? $endDate->format('F j, Y g:i A')
            : 'TBA';

        return view('admin.partials.event-attendance-modal-content', [
            'event' => $event,
            'eventTypeLabel' => $eventTypeLabel,
            'statusKey' => $statusKey,
            'statusLabel' => $statusLabel,
            'formattedStart' => $formattedStart,
            'formattedEnd' => $formattedEnd,
            'sessions' => $sessions,
            'summaryBySession' => $summaryBySession,
            'participants' => $participants,
            'totalRegistered' => $totalRegistered,
            'totalAttended' => $summaryBySession['all']['attended'],
            'totalNotAttended' => $summaryBySession['all']['not_attended'],
            'participantsUrl' => route('admin.participants'),
        ]);
    }
}
