<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendRegistrationVerificationRequest extends FormRequest
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
      'event_id' => ['required', 'integer', 'exists:events,event_id'],
      'first_name' => ['required', 'string', 'max:255'],
      'last_name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'region' => ['required', 'string', 'max:255'],
      'school_from' => ['required', 'string', 'max:255'],
      'school_level' => ['required', 'string', 'max:255'],
      'paper_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
    ];
  }

  /**
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'event_id.required' => 'Please choose an event first.',
      'event_id.exists' => 'The selected event does not exist.',
      'first_name.required' => 'First name is required.',
      'last_name.required' => 'Last name is required.',
      'email.required' => 'Email address is required.',
      'email.email' => 'Please provide a valid email address.',
      'region.required' => 'Region is required.',
      'school_from.required' => 'School from is required.',
      'school_level.required' => 'School level is required.',
      'paper_file.mimes' => 'The research paper must be a PDF file.',
      'paper_file.max' => 'The research paper must not exceed 10 MB.',
    ];
  }
}
