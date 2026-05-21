<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EventOnlineAttendanceTokenService
{
    public function supportsOnlineAttendance(Event $event): bool
    {
        $format = trim((string) $event->attendance_format);

        return in_array($format, ['Online', 'Hybrid'], true);
    }

    public function ensureToken(Event $event): ?string
    {
        if (! $this->supportsOnlineAttendance($event)
            || ! Schema::hasColumn('events', 'online_attendance_token')) {
            return null;
        }

        $existing = trim((string) ($event->online_attendance_token ?? ''));
        if ($existing !== '') {
            return $existing;
        }

        do {
            $token = Str::random(48);
        } while (Event::query()->where('online_attendance_token', $token)->exists());

        Event::query()
            ->where('event_id', $event->event_id)
            ->update(['online_attendance_token' => $token]);

        $event->setAttribute('online_attendance_token', $token);

        return $token;
    }

    public function checkInUrl(Event $event): ?string
    {
        $token = $this->ensureToken($event);
        if ($token === null) {
            return null;
        }

        return route('events.online-attendance.show', ['token' => $token]);
    }
}
