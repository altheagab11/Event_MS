<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRegistrationCodeRequest extends FormRequest
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
      'verification_id' => ['required', 'integer', 'exists:registration_verification_codes,id'],
      'code' => ['required', 'string', 'size:6', 'alpha_num'],
    ];
  }

  /**
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'verification_id.required' => 'Verification session is missing.',
      'verification_id.exists' => 'Verification session not found.',
      'code.required' => 'Verification code is required.',
      'code.size' => 'Verification code must be 6 characters.',
      'code.alpha_num' => 'Verification code must contain letters and numbers only.',
    ];
  }
}
