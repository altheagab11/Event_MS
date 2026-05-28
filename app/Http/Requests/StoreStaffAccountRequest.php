<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStaffAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageAccounts() === true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(User::manageableAccountRoles())],
            'account_status' => ['required', Rule::in([User::STATUS_ACTIVE, User::STATUS_INACTIVE])],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required.',
            'email.unique' => 'This email is already registered.',
            'role.in' => 'Please select a valid role.',
            'account_status.in' => 'Please select a valid account status.',
        ];
    }
}
