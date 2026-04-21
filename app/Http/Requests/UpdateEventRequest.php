<?php

namespace App\Http\Requests;

class UpdateEventRequest extends StoreEventRequest
{
  protected $errorBag = 'editEvent';

  public function rules(): array
  {
    return [
      ...parent::rules(),
      'editing_event_id' => ['nullable', 'integer', 'exists:events,event_id'],
    ];
  }
}
