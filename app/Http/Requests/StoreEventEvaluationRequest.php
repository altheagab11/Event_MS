<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventEvaluationRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'email' => ['required', 'email'],
      'rating' => ['required', 'integer', 'min:1', 'max:5'],
      'comment' => ['nullable', 'string', 'max:2000'],
    ];
  }

  public function messages(): array
  {
    return [
      'email.required' => 'Email is required to verify event participation.',
      'email.email' => 'Please enter a valid email address.',
      'rating.required' => 'A star rating is required.',
      'rating.integer' => 'Rating must be a whole number from 1 to 5.',
      'rating.min' => 'Rating must be at least 1 star.',
      'rating.max' => 'Rating cannot exceed 5 stars.',
    ];
  }
}
