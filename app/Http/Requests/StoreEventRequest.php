<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
      'hosted_by' => ['required', 'string', 'max:255'],
      'attendance_format' => ['required', 'string', 'in:Online,Face-to-Face,Hybrid'],
      'event_type' => ['required', 'string', 'in:School Event,Conference'],
      'start_date' => ['required', 'date'],
      'end_date' => ['required', 'date', 'after_or_equal:start_date'],
      'location' => ['nullable', 'string', 'max:255'],
      'description' => ['nullable', 'string', 'max:5000'],
      'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
      'paper_format_file' => [
        Rule::requiredIf(fn () => $this->input('event_type') === 'Conference'),
        'nullable',
        'file',
        'mimes:pdf,doc,docx',
        'max:10240',
      ],
    ];
  }

  /**
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'event_name.required' => 'Event title is required.',
      'hosted_by.required' => 'Hosted by / department is required.',
      'attendance_format.required' => 'Attendance format is required.',
      'attendance_format.in' => 'Attendance format must be Online, Face-to-Face, or Hybrid.',
      'event_type.required' => 'Event type is required.',
      'event_type.in' => 'Event type must be School Event or Conference.',
      'start_date.required' => 'Start date and time is required.',
      'start_date.date' => 'Please provide a valid start date and time.',
      'end_date.required' => 'End date and time is required.',
      'end_date.date' => 'Please provide a valid end date and time.',
      'end_date.after_or_equal' => 'End date and time must be the same as or after the start date and time.',
      'banner_image.image' => 'The event banner must be an image file.',
      'banner_image.mimes' => 'The event banner must be a JPG, JPEG, PNG, or WEBP file.',
      'banner_image.max' => 'The event banner must not be greater than 5 MB.',
      'paper_format_file.required' => 'Please upload the paper format file for conference events.',
      'paper_format_file.file' => 'The paper format must be a valid file.',
      'paper_format_file.mimes' => 'The paper format must be a PDF, DOC, or DOCX file.',
      'paper_format_file.max' => 'The paper format must not be greater than 10 MB.',
    ];
  }
}
