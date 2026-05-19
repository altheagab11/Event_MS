<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EventAttendanceController extends Controller
{
    public function modal(Event $event): View
    {
        $endDate = $event->end_date ?? $event->event_date;
        if ($endDate !== null && ! $endDate instanceof Carbon) {
            $endDate = Carbon::parse((string) $endDate);
        }
        if ($endDate instanceof Carbon && ! $event->end_date && $event->event_date) {
            $endDate = $endDate->copy()->endOfDay();
        }

        $isArchived = (string) $event->status === 'archived';
        $isDone = ! $isArchived && $endDate instanceof Carbon && $endDate->isPast();
        $event->setAttribute('computed_status', $isArchived ? 'archived' : ($isDone ? 'done' : 'active'));
        $event->setAttribute('computed_status_label', $isArchived ? 'Archived' : ($isDone ? 'Done' : 'Active'));

        $rows = DB::table('event_registrants as er')
            ->where('er.event_id', $event->event_id)
            ->leftJoin('registrations as r', function ($join) use ($event) {
                $join->on('r.event_registrant_id', '=', 'er.event_registrant_id')
                    ->where('r.event_id', '=', $event->event_id);
            })
            ->leftJoin('attendance as a', 'a.registration_id', '=', 'r.registration_id')
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
                'a.check_in_time',
            ])
            ->orderByDesc('er.registration_date')
            ->get();

        $participants = $rows->map(function ($row): array {
            $name = trim((string) $row->first_name.' '.(string) $row->last_name);
            $registrationStatus = (string) ($row->registration_status ?: $row->registrant_status ?: 'pending');
            $checkIn = $row->check_in_time ? Carbon::parse((string) $row->check_in_time) : null;
            $hasAttended = $checkIn !== null;

            return [
                'event_registrant_id' => (int) $row->event_registrant_id,
                'registration_id' => $row->registration_id !== null ? (int) $row->registration_id : null,
                'name' => $name !== '' ? $name : 'Participant',
                'email' => (string) ($row->email ?? ''),
                'user_type' => trim((string) ($row->user_type ?? '')) ?: '—',
                'role' => trim((string) ($row->participant_role ?? '')) ?: '—',
                'registration_status' => $registrationStatus,
                'registration_status_label' => ucfirst($registrationStatus),
                'attendance_status' => $hasAttended ? 'attended' : 'not_attended',
                'attendance_status_label' => $hasAttended ? 'Attended' : 'Not Yet Attended',
                'check_in_time' => $checkIn?->format('M j, Y g:i A'),
                'search_text' => strtolower(trim(implode(' ', [
                    $name,
                    (string) ($row->email ?? ''),
                ]))),
            ];
        });

        $totalRegistered = $participants->count();
        $totalAttended = $participants->where('attendance_status', 'attended')->count();
        $totalNotAttended = $totalRegistered - $totalAttended;

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
            'participants' => $participants,
            'totalRegistered' => $totalRegistered,
            'totalAttended' => $totalAttended,
            'totalNotAttended' => $totalNotAttended,
            'participantsUrl' => route('admin.participants'),
        ]);
    }
}
