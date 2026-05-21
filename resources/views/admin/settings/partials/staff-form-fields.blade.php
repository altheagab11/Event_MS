@once
    <style>
        .staff-form-select {
            color-scheme: dark;
            appearance: none;
            background-color: rgb(13 27 49 / 0.95);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2360A5FA'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
            padding-right: 2.75rem;
        }

        .staff-form-select option {
            background-color: #0d1b31;
            color: #f8fafc;
        }
    </style>
@endonce

@php
    $inputClass = 'mt-2 w-full max-w-full rounded-2xl border border-[#1E3357] bg-[#0D1B31]/80 px-4 py-3 text-sm font-medium text-[#F8FAFC] outline-none transition focus:border-[#60A5FA]/50 focus:ring-2 focus:ring-[#60A5FA]/20';
    $selectClass = $inputClass.' staff-form-select cursor-pointer bg-[#0D1B31] text-[#F8FAFC]';
    $labelClass = 'text-xs font-black uppercase tracking-widest text-[#94A3B8]';
    $requirePassword = ($mode ?? 'create') === 'create';
@endphp

<div class="mx-auto w-full max-w-2xl space-y-6">
    <div>
        <label class="{{ $labelClass }}">Full Name</label>
        <input type="text" name="full_name" value="{{ old('full_name') }}" class="{{ $inputClass }}" required>
    </div>

    <div>
        <label class="{{ $labelClass }}">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" class="{{ $inputClass }}" required>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <label class="{{ $labelClass }}">Password</label>
            <input type="password" name="password" class="{{ $inputClass }}" {{ $requirePassword ? 'required' : '' }} autocomplete="new-password">
            @unless ($requirePassword)
                <p class="mt-2 text-xs leading-relaxed text-[#64748B]">Leave blank to keep current password.</p>
            @endunless
        </div>
        <div>
            <label class="{{ $labelClass }}">Confirm Password</label>
            <input type="password" name="password_confirmation" class="{{ $inputClass }}" {{ $requirePassword ? 'required' : '' }} autocomplete="new-password">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <label class="{{ $labelClass }}">Role</label>
            <select name="role" class="{{ $selectClass }}" required>
                <option value="admin" class="bg-[#0D1B31] text-[#F8FAFC]" @selected(old('role') === 'admin')>Admin</option>
                <option value="staff" class="bg-[#0D1B31] text-[#F8FAFC]" @selected(old('role', 'staff') === 'staff')>Staff</option>
            </select>
        </div>
        <div>
            <label class="{{ $labelClass }}">Account Status</label>
            <select name="account_status" class="{{ $selectClass }}" required>
                <option value="active" class="bg-[#0D1B31] text-[#F8FAFC]" @selected(old('account_status', 'active') === 'active')>Active</option>
                <option value="inactive" class="bg-[#0D1B31] text-[#F8FAFC]" @selected(old('account_status') === 'inactive')>Inactive</option>
            </select>
        </div>
    </div>
</div>
