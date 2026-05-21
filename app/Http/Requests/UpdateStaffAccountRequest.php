<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageAccounts() === true;
    }

    public function rules(): array
    {
        $staff = $this->route('staff');
        $allowedRoles = $staff instanceof User && $staff->isSuperAdmin()
            ? [User::ROLE_SUPER_ADMIN]
            : User::manageableAccountRoles();

        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($staff?->id),
            ],
            'role' => ['required', Rule::in($allowedRoles)],
            'account_status' => ['required', Rule::in([User::STATUS_ACTIVE, User::STATUS_INACTIVE])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
