<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'event_name' => ['required', 'string', 'max:255'],
      'event_type' => ['required', 'string', 'in:School Event,Conference'],
      'event_date' => ['required', 'date'],
      'location' => ['nullable', 'string', 'max:255'],
      'description' => ['nullable', 'string', 'max:5000'],
      'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
    ];
  }

  /**
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'event_name.required' => 'Event title is required.',
      'event_type.required' => 'Event type is required.',
      'event_type.in' => 'Event type must be School Event or Conference.',
      'event_date.required' => 'Event date is required.',
      'event_date.date' => 'Please provide a valid event date.',
      'banner_image.image' => 'The event banner must be an image file.',
      'banner_image.mimes' => 'The event banner must be a JPG, JPEG, PNG, or WEBP file.',
      'banner_image.max' => 'The event banner must not be greater than 5 MB.',
    ];
  }
}
