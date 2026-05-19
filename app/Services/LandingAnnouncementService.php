<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LandingAnnouncementService
{
    /**
     * @return list<array{
     *   id: int,
     *   emoji: string,
     *   title: string,
     *   description: string,
     *   type: string,
     *   eventId: int,
     *   buttonText: string,
     *   cta: string,
     *   can_register: bool
     * }>
     */
    public function buildFromEvents(Collection $events, int $limit = 3): array
    {
        return $events
            ->filter(fn (Event $event): bool => (string) $event->status !== 'archived')
            ->sortBy(fn (Event $event): array => $this->sortKey($event))
            ->take($limit)
            ->map(fn (Event $event): array => $this->mapEvent($event))
            ->values()
            ->all();
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function sortKey(Event $event): array
    {
        $card = $event->publicRegistrationCardState();
        $start = $event->start_date ?? $event->event_date;
        if ($start !== null && ! $start instanceof Carbon) {
            $start = Carbon::parse((string) $start);
        }

        return [
            $card['can_register'] ? 0 : 1,
            $start instanceof Carbon ? $start->timestamp : PHP_INT_MAX,
        ];
    }

    /**
     * @return array{
     *   id: int,
     *   emoji: string,
     *   title: string,
     *   description: string,
     *   type: string,
     *   eventId: int,
     *   buttonText: string,
     *   cta: string,
     *   can_register: bool
     * }
     */
    private function mapEvent(Event $event): array
    {
        $card = $event->publicRegistrationCardState();
        $canRegister = $card['can_register'];
        $isConference = strcasecmp((string) $event->event_type, 'Conference') === 0;
        $preferCatalogCta = str_contains(strtolower((string) $event->event_name), 'intramural');
        $showRegisterCta = $canRegister && ! $preferCatalogCta;

        return [
            'id' => (int) $event->event_id,
            'emoji' => $this->emojiFor($event, $isConference),
            'title' => (string) $event->event_name,
            'description' => $this->descriptionFor($event, $isConference, $canRegister),
            'type' => $isConference && $canRegister ? 'important' : 'info',
            'eventId' => (int) $event->event_id,
            'buttonText' => $this->buttonTextFor($event, $isConference, $canRegister, $preferCatalogCta),
            'cta' => $showRegisterCta ? 'register' : 'events',
            'can_register' => $showRegisterCta,
        ];
    }

    private function emojiFor(Event $event, bool $isConference): string
    {
        if ($isConference) {
            return '📢';
        }

        $name = strtolower((string) $event->event_name);
        if (str_contains($name, 'intramural') || str_contains($name, 'sports')) {
            return '🏀';
        }

        return '📝';
    }

    private function descriptionFor(Event $event, bool $isConference, bool $canRegister): string
    {
        $custom = trim((string) $event->description);
        if ($custom !== '') {
            return Str::limit($custom, 180);
        }

        $deadline = $this->registrationDeadlineLabel($event);

        if ($isConference && $canRegister) {
            return "Call for Papers for {$event->event_name} is now open! Register and submit your PDF manuscripts"
                .($deadline ? " until {$deadline}." : '.');
        }

        if ($canRegister) {
            $location = trim((string) $event->location);

            return "Registration is now open for {$event->event_name}."
                .($location !== '' ? " The event will be held at {$location}." : ' Secure your slot early.');
        }

        $cardState = $event->publicRegistrationCardState();
        if ($cardState['state'] === 'completed') {
            return "{$event->event_name} has concluded. Browse the event catalog for other upcoming activities.";
        }

        return "Updates and reminders for {$event->event_name}. View the event catalog for schedule and attendance details.";
    }

    private function buttonTextFor(Event $event, bool $isConference, bool $canRegister, bool $preferCatalogCta): string
    {
        if ($preferCatalogCta) {
            return 'View '.Str::limit((string) $event->event_name, 28, '');
        }

        if (! $canRegister) {
            return 'View Events';
        }

        if ($isConference) {
            return 'Register & Submit Paper';
        }

        return 'Register Now';
    }

    private function registrationDeadlineLabel(Event $event): ?string
    {
        $start = $event->start_date ?? $event->event_date;
        if ($start === null) {
            return null;
        }

        $start = $start instanceof Carbon ? $start : Carbon::parse((string) $start);

        return $start->format('F j, Y');
    }
}
