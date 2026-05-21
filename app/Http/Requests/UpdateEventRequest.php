<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends StoreEventRequest
{
  protected $errorBag = 'editEvent';

  public function rules(): array
  {
    $rules = parent::rules();
    $rules['paper_format_file'] = [
      Rule::requiredIf(function (): bool {
        if ($this->input('event_type') !== 'Conference') {
          return false;
        }

        $eventId = (int) $this->input('editing_event_id');
        if ($eventId <= 0) {
          return true;
        }

        $event = Event::query()->find($eventId);

        return $event === null || ! $event->paper_format_file;
      }),
      'nullable',
      'file',
      'mimes:pdf,doc,docx',
      'max:10240',
    ];

    return [
      ...$rules,
      'editing_event_id' => ['nullable', 'integer', 'exists:events,event_id'],
    ];
  }
}
