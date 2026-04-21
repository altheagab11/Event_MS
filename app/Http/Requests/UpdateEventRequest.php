<?php

namespace App\Http\Requests;

class UpdateEventRequest extends StoreEventRequest
{
  protected $errorBag = 'editEvent';

  public function rules(): array
  {
    return [
      'event_name' => ['required', 'string', 'max:255'],
      'event_type' => ['required', 'string', 'in:School Event,Conference'],
      'event_date' => ['required', 'date'],
      'location' => ['nullable', 'string', 'max:255'],
      'description' => ['nullable', 'string', 'max:5000'],
      'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
      'hosted_by' => ['nullable', 'string', 'max:255'],
      'attendance_format' => ['nullable', 'string', 'in:Online,Face-to-Face,Hybrid'],
      'start_date' => ['nullable', 'date'],
      'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
      'editing_event_id' => ['nullable', 'integer', 'exists:events,event_id'],
    ];
  }

  public function messages(): array
  {
    return [
      ...parent::messages(),
      'event_date.required' => 'Event date is required.',
      'event_date.date' => 'Please provide a valid event date.',
    ];
  }
}
