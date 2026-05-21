<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
    $eventId = (int) $this->input('event_id');
    $event = Event::query()->where('event_id', $eventId)->first();
    $isHybrid = $event !== null && $event->isHybridAttendanceFormat();
    $isConference = $event !== null && (string) $event->event_type === 'Conference';
    $isSchoolEvent = $event !== null && (string) $event->event_type === 'School Event';
    $role = strtolower(trim((string) $this->input('school_level')));
    $requiresPaper = $isConference && $role === 'presentor';

    return [
      'event_id' => ['required', 'integer', 'exists:events,event_id'],
      'first_name' => ['required', 'string', 'max:255'],
      'last_name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'region' => ['required', 'string', 'max:255'],
      'school_from' => ['required', 'string', 'max:255'],
      'school_level' => [
        'required',
        'string',
        'max:255',
        Rule::when($isConference, ['in:Presentor,Participant']),
        Rule::when($isSchoolEvent, ['in:Exhibitor,Participant']),
      ],
      'paper_file' => [Rule::requiredIf($requiresPaper), 'nullable', 'file', 'mimes:pdf', 'max:10240'],
      'attendance_mode' => [
        Rule::requiredIf($isHybrid),
        'nullable',
        'string',
        'in:Face-to-Face,Online',
      ],
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
      'region.required' => 'School / University is required.',
      'school_from.required' => 'User type is required.',
      'school_level.required' => 'Role is required.',
      'paper_file.required' => 'A research paper PDF is required when registering as a Presentor.',
      'school_level.in' => 'Please select a valid role for this event.',
      'paper_file.mimes' => 'The research paper must be a PDF file.',
      'paper_file.max' => 'The research paper must not exceed 10 MB.',
      'attendance_mode.required' => 'Please select how you will attend this event.',
      'attendance_mode.in' => 'Attendance mode must be Face-to-Face or Online.',
    ];
  }
}
