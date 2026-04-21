<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
  public function landing()
  {
    $events = Event::query()
      ->where(function ($query) {
        $query->whereNull('status')
          ->orWhere('status', '!=', 'archived');
      })
      ->orderBy('event_date')
      ->get()
      ->map(function (Event $event): array {
        $eventDate = $event->event_date instanceof Carbon
          ? $event->event_date
          : Carbon::parse($event->event_date);

        return [
          'id' => $event->event_id,
          'title' => $event->event_name,
          'type' => (string) $event->event_type === 'Conference' ? 'Conference Event' : 'School Event',
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
    $payload['event_date'] = $payload['start_date'];

    if ($request->hasFile('banner_image')) {
      $payload['banner_image'] = $request->file('banner_image')->store('event-banners', 'public');
    }

    Event::query()->create($payload);

    return redirect()
      ->route('admin.events')
      ->with('status', 'Event created successfully.');
  }

  public function update(UpdateEventRequest $request, Event $event): RedirectResponse
  {
    $payload = $request->safe()->except(['banner_image', 'editing_event_id']);
    $payload['event_date'] = $payload['start_date'];

    if ($request->hasFile('banner_image')) {
      $payload['banner_image'] = $request->file('banner_image')->store('event-banners', 'public');
      $this->deleteStoredBannerIfLocal($event->banner_image);
    }

    $event->fill($payload)->save();

    return redirect()
      ->route('admin.events')
      ->with('status', 'Event updated successfully.');
  }

  public function archive(Event $event): RedirectResponse
  {
    $event->forceFill(['status' => 'archived'])->save();

    return redirect()
      ->route('admin.events')
      ->with('status', 'Event archived successfully.');
  }

  private function deleteStoredBannerIfLocal(?string $bannerImage): void
  {
    if (! $bannerImage) {
      return;
    }

    if (filter_var($bannerImage, FILTER_VALIDATE_URL)) {
      return;
    }

    $relativePath = ltrim($bannerImage, '/');
    $relativePath = preg_replace('#^storage/#', '', $relativePath) ?? $relativePath;
    $relativePath = preg_replace('#^public/#', '', $relativePath) ?? $relativePath;

    if (Storage::disk('public')->exists($relativePath)) {
      Storage::disk('public')->delete($relativePath);
    }
  }
}
