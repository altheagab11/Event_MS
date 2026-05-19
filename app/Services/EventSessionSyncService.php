<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EventSessionSyncService
{
    public function syncForEvent(Event $event): void
    {
        $payloads = $this->buildSessionPayloads($event);

        if ($payloads->isEmpty()) {
            EventSession::query()
                ->where('event_id', $event->event_id)
                ->whereDoesntHave('attendanceRecords')
                ->delete();

            return;
        }

        DB::transaction(function () use ($event, $payloads): void {
            $dates = [];

            foreach ($payloads as $payload) {
                $dates[] = $payload['session_date'];

                EventSession::query()->updateOrCreate(
                    [
                        'event_id' => $event->event_id,
                        'session_date' => $payload['session_date'],
                    ],
                    [
                        'start_time' => $payload['start_time'],
                        'end_time' => $payload['end_time'],
                        'session_label' => $payload['session_label'],
                    ]
                );
            }

            EventSession::query()
                ->where('event_id', $event->event_id)
                ->whereNotIn('session_date', $dates)
                ->whereDoesntHave('attendanceRecords')
                ->delete();
        });
    }

    public function syncAllEvents(): void
    {
        Event::query()->each(function (Event $event): void {
            $this->syncForEvent($event);
        });
    }

    public function findSessionForToday(int $eventId, ?Carbon $now = null): ?EventSession
    {
        $now = $now ?? now();

        return EventSession::query()
            ->where('event_id', $eventId)
            ->whereDate('session_date', $now->toDateString())
            ->first();
    }

    /**
     * @return Collection<int, array{
     *   session_date: string,
     *   start_time: string,
     *   end_time: string,
     *   session_label: string
     * }>
     */
    public function buildSessionPayloads(Event $event): Collection
    {
        $startAt = $event->start_date ?? $event->event_date;
        $endAt = $event->end_date ?? $event->event_date;

        if ($startAt === null || $endAt === null) {
            return collect();
        }

        $startAt = $startAt instanceof Carbon ? $startAt : Carbon::parse((string) $startAt);
        $endAt = $endAt instanceof Carbon ? $endAt : Carbon::parse((string) $endAt);

        $rangeStart = $startAt->copy()->startOfDay();
        $rangeEnd = $endAt->copy()->startOfDay();

        if ($rangeEnd->lt($rangeStart)) {
            $rangeEnd = $rangeStart->copy();
        }

        $payloads = collect();
        $dayNumber = 1;

        for ($date = $rangeStart->copy(); $date->lte($rangeEnd); $date->addDay()) {
            $isFirst = $dayNumber === 1;
            $isLast = $date->toDateString() === $rangeEnd->toDateString();

            $payloads->push([
                'session_date' => $date->toDateString(),
                'start_time' => $isFirst ? $startAt->format('H:i:s') : '00:00:00',
                'end_time' => $isLast ? $endAt->format('H:i:s') : '23:59:59',
                'session_label' => 'Day '.$dayNumber.' - '.$date->format('M j'),
            ]);

            $dayNumber++;
        }

        return $payloads;
    }
}
