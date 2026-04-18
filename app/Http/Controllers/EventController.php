<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
  public function landing()
  {
    $events = Event::query()
      ->orderBy('event_date')
      ->get()
      ->map(function (Event $event): array {
        $eventDate = $event->event_date instanceof Carbon
          ? $event->event_date
          : Carbon::parse($event->event_date);

        return [
          'id' => $event->event_id,
          'title' => $event->event_name,
          'type' => 'School Event',
          'date' => $eventDate->format('F j, Y'),
          'month' => $eventDate->month - 1,
          'year' => $eventDate->format('Y'),
          'location' => $event->location ?: 'TBA',
          'description' => $event->description ?: 'No description available.',
          'status' => $eventDate->isPast() ? 'ended' : 'active',
          'image' => $event->banner_url
            ?: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=60',
        ];
      })
      ->values();

    return view('landingpage', [
      'events' => $events,
    ]);
  }

  public function index()
  {
    $events = Event::query()
      ->orderByDesc('event_date')
      ->get();

    return view('admin.events', [
      'events' => $events,
    ]);
  }

  public function store(StoreEventRequest $request)
  {
    $payload = $request->safe()->except('banner_image');

    if ($request->hasFile('banner_image')) {
      $payload['banner_image'] = $request->file('banner_image')->store('event-banners', 'public');
    }

    Event::query()->create($payload);

    return redirect()
      ->route('admin.events')
      ->with('status', 'Event created successfully.');
  }
}
