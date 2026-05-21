@extends('layouts.app')

@php
    $inputClass = 'mt-2 w-full rounded-2xl border border-[#1E3357] bg-[#0D1B31]/80 px-4 py-3 text-sm font-medium text-[#F8FAFC] outline-none transition focus:border-[#60A5FA]/50 focus:ring-2 focus:ring-[#60A5FA]/20';
    $selectClass = $inputClass.' staff-form-select cursor-pointer bg-[#0D1B31] text-[#F8FAFC]';
    $labelClass = 'text-xs font-black uppercase tracking-widest text-[#94A3B8]';
@endphp

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

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0F1E36] via-[#132B4A] to-[#0F1E36] font-sans text-[#F8FAFC]">
    <div class="flex">
        @include('admin.partials.sidebar')

        <main class="ml-[270px] min-h-screen w-full">
            @include('admin.partials.topbar', ['topbarTitle' => 'Settings'])

            <section class="px-9 py-10">
                <div class="flex items-center gap-3">
                    <div class="h-7 w-1 rounded-full bg-[#60A5FA]"></div>
                    <div>
                        <h1 class="text-[28px] font-black tracking-tight text-[#F8FAFC]">SETTINGS</h1>
                        <p class="mt-2 text-sm text-[#CBD5E1]">Manage your profile{{ $isSuperAdmin ? ', portal accounts, permissions, and activity logs' : '' }}.</p>
                    </div>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-[#22C55E]/30 bg-[#14532D]/30 px-5 py-4 text-sm font-semibold text-[#86EFAC]">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-[#EF4444]/30 bg-[#7F1D1D]/20 px-5 py-4 text-sm text-[#FCA5A5]">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('admin.settings', ['tab' => 'profile']) }}"
                       class="rounded-2xl px-5 py-3 text-sm font-black uppercase tracking-wide transition {{ $activeTab === 'profile' ? 'bg-[#3B82F6] text-white shadow-sm' : 'border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#94A3B8] hover:text-[#F8FAFC]' }}">
                        Profile Settings
                    </a>
                    @if ($isSuperAdmin)
                        <a href="{{ route('admin.settings', ['tab' => 'accounts']) }}"
                           class="rounded-2xl px-5 py-3 text-sm font-black uppercase tracking-wide transition {{ $activeTab === 'accounts' ? 'bg-[#3B82F6] text-white shadow-sm' : 'border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#94A3B8] hover:text-[#F8FAFC]' }}">
                            Account Management
                        </a>
                        <a href="{{ route('admin.settings', ['tab' => 'roles']) }}"
                           class="rounded-2xl px-5 py-3 text-sm font-black uppercase tracking-wide transition {{ $activeTab === 'roles' ? 'bg-[#3B82F6] text-white shadow-sm' : 'border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#94A3B8] hover:text-[#F8FAFC]' }}">
                            Role & Permissions
                        </a>
                        <a href="{{ route('admin.settings', ['tab' => 'logs']) }}"
                           class="rounded-2xl px-5 py-3 text-sm font-black uppercase tracking-wide transition {{ $activeTab === 'logs' ? 'bg-[#3B82F6] text-white shadow-sm' : 'border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#94A3B8] hover:text-[#F8FAFC]' }}">
                            Activity Logs
                        </a>
                    @endif
                </div>

                @if ($activeTab === 'profile')
                    <div class="mt-8 max-w-3xl rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 p-8 shadow-sm">
                        <h2 class="text-xl font-black text-[#F8FAFC]">Profile Settings</h2>
                        <p class="mt-2 text-sm text-[#94A3B8]">Update your account name, email, and password.</p>

                        <form method="POST" action="{{ route('admin.settings.profile.update') }}" class="mt-8 space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="profile_full_name" class="{{ $labelClass }}">Full Name</label>
                                <input id="profile_full_name" type="text" name="full_name" value="{{ old('full_name', $profile['full_name']) }}" class="{{ $inputClass }}" required>
                            </div>

                            <div>
                                <label for="profile_email" class="{{ $labelClass }}">Email Address</label>
                                <input id="profile_email" type="email" name="email" value="{{ old('email', $profile['email']) }}" class="{{ $inputClass }}" required>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div>
                                    <label for="profile_password" class="{{ $labelClass }}">New Password</label>
                                    <input id="profile_password" type="password" name="password" class="{{ $inputClass }}" autocomplete="new-password">
                                    <p class="mt-2 text-xs text-[#64748B]">Leave blank to keep current password.</p>
                                </div>
                                <div>
                                    <label for="profile_password_confirmation" class="{{ $labelClass }}">Confirm Password</label>
                                    <input id="profile_password_confirmation" type="password" name="password_confirmation" class="{{ $inputClass }}" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="rounded-2xl border border-[#1E3357] bg-[#0D1B31]/50 px-5 py-4 text-sm text-[#CBD5E1]">
                                <p><span class="font-black text-[#F8FAFC]">Role:</span> {{ $profile['role_label'] }}</p>
                                <p class="mt-1"><span class="font-black text-[#F8FAFC]">Account Status:</span> {{ ucfirst($profile['account_status']) }}</p>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="rounded-2xl bg-[#3B82F6] px-8 py-3 text-sm font-black uppercase tracking-wide text-white transition hover:bg-[#2563EB]">
                                    Save Profile
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($isSuperAdmin && $activeTab === 'accounts')
                    <div class="mt-8">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-xl font-black text-[#F8FAFC]">Account Management</h2>
                                <p class="mt-2 text-sm text-[#94A3B8]">Create, edit, deactivate, and reset passwords for admin and staff portal accounts.</p>
                            </div>
                            <button type="button" id="openCreateStaffModal" class="self-start rounded-2xl bg-[#3B82F6] px-6 py-3 text-sm font-black uppercase tracking-wide text-white transition hover:bg-[#2563EB]">
                                Create Account
                            </button>
                        </div>

                        <div class="mt-8 overflow-hidden rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead class="border-b border-[#1E3357] bg-[#0D1B31]/60 text-xs font-black uppercase tracking-widest text-[#94A3B8]">
                                        <tr>
                                            <th class="px-6 py-4">User</th>
                                            <th class="px-6 py-4">Email</th>
                                            <th class="px-6 py-4">Role</th>
                                            <th class="px-6 py-4">Status</th>
                                            <th class="px-6 py-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#1E3357]">
                                        @forelse ($staffAccounts as $account)
                                            <tr class="text-[#CBD5E1]">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#3B82F6] text-sm font-black text-white">
                                                            {{ $account['initials'] }}
                                                        </div>
                                                        <span class="font-bold text-[#F8FAFC]">{{ $account['full_name'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">{{ $account['email'] }}</td>
                                                <td class="px-6 py-4">
                                                    <span class="rounded-xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-3 py-1 text-xs font-black uppercase text-[#60A5FA]">
                                                        {{ $account['role_label'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="rounded-xl px-3 py-1 text-xs font-black uppercase {{ $account['account_status'] === 'active' ? 'bg-[#14532D]/40 text-[#86EFAC]' : 'bg-[#7F1D1D]/30 text-[#FCA5A5]' }}">
                                                        {{ $account['status_label'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-wrap justify-end gap-2">
                                                        @if ($account['is_self'])
                                                            <span class="rounded-xl border border-[#60A5FA]/30 bg-[#13284A]/70 px-3 py-2 text-xs font-black uppercase text-[#60A5FA]">
                                                                Current Account
                                                            </span>
                                                            <button
                                                                type="button"
                                                                class="reset-staff-btn rounded-xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-3 py-2 text-xs font-black uppercase text-[#F8FAFC] transition hover:border-[#60A5FA]/50"
                                                                data-staff-id="{{ $account['id'] }}"
                                                                data-staff-name="{{ $account['full_name'] }}"
                                                            >
                                                                Reset Password
                                                            </button>
                                                        @else
                                                            @unless ($account['is_super_admin'] ?? false)
                                                                <button
                                                                    type="button"
                                                                    class="edit-staff-btn rounded-xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-3 py-2 text-xs font-black uppercase text-[#F8FAFC] transition hover:border-[#60A5FA]/50"
                                                                    data-staff='@json($account)'
                                                                >
                                                                    Edit
                                                                </button>
                                                            @endunless
                                                            @unless ($account['is_super_admin'] ?? false)
                                                                <button
                                                                    type="button"
                                                                    class="reset-staff-btn rounded-xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-3 py-2 text-xs font-black uppercase text-[#F8FAFC] transition hover:border-[#60A5FA]/50"
                                                                    data-staff-id="{{ $account['id'] }}"
                                                                    data-staff-name="{{ $account['full_name'] }}"
                                                                >
                                                                    Reset Password
                                                                </button>
                                                            @endunless
                                                            @if ($account['account_status'] === 'active')
                                                                <form method="POST" action="{{ route('admin.settings.staff.deactivate', ['staff' => $account['id']]) }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="rounded-xl border border-[#EF4444]/30 bg-[#7F1D1D]/20 px-3 py-2 text-xs font-black uppercase text-[#FCA5A5] transition hover:border-[#EF4444]/50">
                                                                        Deactivate
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form method="POST" action="{{ route('admin.settings.staff.activate', ['staff' => $account['id']]) }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="rounded-xl border border-[#22C55E]/30 bg-[#14532D]/30 px-3 py-2 text-xs font-black uppercase text-[#86EFAC] transition hover:border-[#22C55E]/50">
                                                                        Activate
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-12 text-center text-[#94A3B8]">No portal accounts found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($isSuperAdmin && $activeTab === 'roles')
                    <div class="mt-8 max-w-5xl rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 p-8 shadow-sm">
                        <h2 class="text-xl font-black text-[#F8FAFC]">Role & Permissions</h2>
                        <p class="mt-2 text-sm text-[#94A3B8]">Access rules enforced on routes, middleware, and sidebar navigation.</p>

                        <div class="mt-8 overflow-x-auto rounded-2xl border border-[#1E3357]">
                            <table class="min-w-full text-sm">
                                <thead class="border-b border-[#1E3357] bg-[#0D1B31]/60 text-xs font-black uppercase tracking-widest text-[#94A3B8]">
                                    <tr>
                                        <th class="px-6 py-4 text-left">Feature</th>
                                        <th class="px-6 py-4 text-center">Super Admin</th>
                                        <th class="px-6 py-4 text-center">Admin</th>
                                        <th class="px-6 py-4 text-center">Staff</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#1E3357] text-[#CBD5E1]">
                                    @foreach ($permissionMatrix as $row)
                                        <tr>
                                            <td class="px-6 py-4 font-semibold text-[#F8FAFC]">{{ $row['feature'] }}</td>
                                            <td class="px-6 py-4 text-center">
                                                @if ($row['super_admin'])
                                                    <span class="font-black text-[#86EFAC]">Yes</span>
                                                @else
                                                    <span class="text-[#64748B]">No</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if ($row['admin'])
                                                    <span class="font-black text-[#86EFAC]">Yes</span>
                                                @else
                                                    <span class="text-[#64748B]">No</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if ($row['staff'])
                                                    <span class="font-black text-[#86EFAC]">Yes</span>
                                                @else
                                                    <span class="text-[#64748B]">No</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($isSuperAdmin && $activeTab === 'logs')
                    <div class="mt-8">
                        <div>
                            <h2 class="text-xl font-black text-[#F8FAFC]">Activity Logs</h2>
                            <p class="mt-2 text-sm text-[#94A3B8]">System audit trail for authentication, accounts, events, participants, and certificates.</p>
                        </div>

                        <form method="GET" action="{{ route('admin.settings') }}" class="mt-6 flex flex-wrap items-end gap-4 rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 p-6">
                            <input type="hidden" name="tab" value="logs">

                            <div class="min-w-[160px] flex-1">
                                <label class="{{ $labelClass }}">Role</label>
                                <select name="log_role" class="{{ $selectClass }}">
                                    <option value="">All roles</option>
                                    @foreach ($logRoles as $role)
                                        <option value="{{ $role }}" @selected($logFilters['log_role'] === $role)>{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="min-w-[160px] flex-1">
                                <label class="{{ $labelClass }}">Module</label>
                                <select name="log_module" class="{{ $selectClass }}">
                                    <option value="">All modules</option>
                                    @foreach ($logModules as $module)
                                        <option value="{{ $module }}" @selected($logFilters['log_module'] === $module)>{{ $module }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="min-w-[220px] flex-[2]">
                                <label class="{{ $labelClass }}">Search</label>
                                <input type="text" name="log_search" value="{{ $logFilters['log_search'] }}" placeholder="User, action, or description..." class="{{ $inputClass }}">
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="rounded-2xl bg-[#3B82F6] px-6 py-3 text-sm font-black uppercase tracking-wide text-white transition hover:bg-[#2563EB]">
                                    Filter
                                </button>
                                <a href="{{ route('admin.settings', ['tab' => 'logs']) }}" class="rounded-2xl border border-[#60A5FA]/25 px-6 py-3 text-sm font-black uppercase text-[#F8FAFC] transition hover:border-[#60A5FA]/50">
                                    Reset
                                </a>
                            </div>
                        </form>

                        <div class="mt-8 overflow-hidden rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead class="border-b border-[#1E3357] bg-[#0D1B31]/60 text-xs font-black uppercase tracking-widest text-[#94A3B8]">
                                        <tr>
                                            <th class="px-6 py-4">Date & Time</th>
                                            <th class="px-6 py-4">User</th>
                                            <th class="px-6 py-4">Role</th>
                                            <th class="px-6 py-4">Action</th>
                                            <th class="px-6 py-4">Module</th>
                                            <th class="px-6 py-4">Description</th>
                                            <th class="px-6 py-4">IP Address</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#1E3357]">
                                        @forelse ($activityLogs as $log)
                                            <tr class="text-[#CBD5E1]">
                                                <td class="whitespace-nowrap px-6 py-4 text-xs">{{ $log['created_at'] }}</td>
                                                <td class="px-6 py-4 font-semibold text-[#F8FAFC]">{{ $log['user_name'] }}</td>
                                                <td class="px-6 py-4">
                                                    <span class="rounded-xl border border-[#60A5FA]/25 bg-[#13284A]/70 px-3 py-1 text-xs font-black uppercase text-[#60A5FA]">
                                                        {{ $log['user_role'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 font-bold text-[#F8FAFC]">{{ $log['action'] }}</td>
                                                <td class="px-6 py-4">{{ $log['module'] }}</td>
                                                <td class="max-w-xs px-6 py-4 text-xs leading-relaxed">{{ $log['description'] }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-xs font-mono">{{ $log['ip_address'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-12 text-center text-[#94A3B8]">No activity logs found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($activityLogs->hasPages())
                                <div class="border-t border-[#1E3357] px-6 py-4">
                                    {{ $activityLogs->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </section>
        </main>
    </div>
</div>

@if ($isSuperAdmin)
    {{-- Create Staff Modal --}}
    <div id="createStaffModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4 backdrop-blur-sm sm:p-6">
        <div class="relative mx-auto flex max-h-[92vh] w-[90%] max-w-4xl flex-col overflow-hidden rounded-3xl border border-[#60A5FA]/25 bg-[#10213A] shadow-2xl shadow-black/40">
            <div class="flex shrink-0 items-start justify-between border-b border-[#1E3357] bg-[#0D1B31] px-6 py-5 sm:px-8 sm:py-6">
                <div class="pr-4">
                    <h2 class="text-xl font-black uppercase tracking-wide text-[#F8FAFC] sm:text-2xl">Create Account</h2>
                    <p class="mt-2 text-sm text-[#60A5FA]">Add a new admin or staff portal account.</p>
                </div>
                <button type="button" class="close-staff-modal flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#F8FAFC]">×</button>
            </div>
            <form method="POST" action="{{ route('admin.settings.staff.store') }}" class="flex min-h-0 flex-1 flex-col overflow-y-auto">
                <div class="space-y-6 px-6 py-6 sm:px-8 sm:py-7">
                    @csrf
                    @include('admin.settings.partials.staff-form-fields', ['mode' => 'create'])
                </div>
                <div class="flex shrink-0 flex-wrap justify-end gap-3 border-t border-[#1E3357] bg-[#0D1B31]/40 px-6 py-5 sm:px-8">
                    <button type="button" class="close-staff-modal rounded-2xl border border-[#60A5FA]/25 px-6 py-3 text-sm font-black uppercase text-[#F8FAFC]">Cancel</button>
                    <button type="submit" class="rounded-2xl bg-[#3B82F6] px-6 py-3 text-sm font-black uppercase text-white transition hover:bg-[#2563EB]">Create Account</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Staff Modal --}}
    <div id="editStaffModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4 backdrop-blur-sm sm:p-6">
        <div class="relative mx-auto flex max-h-[92vh] w-[90%] max-w-4xl flex-col overflow-hidden rounded-3xl border border-[#60A5FA]/25 bg-[#10213A] shadow-2xl shadow-black/40">
            <div class="flex shrink-0 items-start justify-between border-b border-[#1E3357] bg-[#0D1B31] px-6 py-5 sm:px-8 sm:py-6">
                <div class="pr-4">
                    <h2 class="text-xl font-black uppercase tracking-wide text-[#F8FAFC] sm:text-2xl">Edit Account</h2>
                    <p class="mt-2 text-sm text-[#60A5FA]">Update staff details and account status.</p>
                </div>
                <button type="button" class="close-staff-modal flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#F8FAFC]">×</button>
            </div>
            <form id="editStaffForm" method="POST" action="#" class="flex min-h-0 flex-1 flex-col overflow-y-auto">
                <div class="space-y-6 px-6 py-6 sm:px-8 sm:py-7">
                    @csrf
                    @method('PUT')
                    @include('admin.settings.partials.staff-form-fields', ['mode' => 'edit'])
                </div>
                <div class="flex shrink-0 flex-wrap justify-end gap-3 border-t border-[#1E3357] bg-[#0D1B31]/40 px-6 py-5 sm:px-8">
                    <button type="button" class="close-staff-modal rounded-2xl border border-[#60A5FA]/25 px-6 py-3 text-sm font-black uppercase text-[#F8FAFC]">Cancel</button>
                    <button type="submit" class="rounded-2xl bg-[#3B82F6] px-6 py-3 text-sm font-black uppercase text-white transition hover:bg-[#2563EB]">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reset Password Modal --}}
    <div id="resetStaffModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4 backdrop-blur-sm sm:p-6">
        <div class="relative mx-auto flex max-h-[92vh] w-[90%] max-w-lg flex-col overflow-hidden rounded-3xl border border-[#60A5FA]/25 bg-[#10213A] shadow-2xl shadow-black/40">
            <div class="flex shrink-0 items-start justify-between border-b border-[#1E3357] bg-[#0D1B31] px-6 py-5 sm:px-8 sm:py-6">
                <div class="pr-4">
                    <h2 class="text-xl font-black uppercase tracking-wide text-[#F8FAFC] sm:text-2xl">Reset Password</h2>
                    <p id="resetStaffSubtitle" class="mt-2 text-sm text-[#60A5FA]">Set a new password for the selected account.</p>
                </div>
                <button type="button" class="close-reset-modal flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[#60A5FA]/20 bg-[#13284A]/70 text-[#F8FAFC]">×</button>
            </div>
            <form id="resetStaffForm" method="POST" action="#" class="flex min-h-0 flex-1 flex-col overflow-y-auto">
                <div class="grid grid-cols-1 gap-6 px-6 py-6 sm:px-8 sm:py-7 md:grid-cols-2">
                    @csrf
                    @method('PATCH')
                    <div class="md:col-span-1">
                        <label class="{{ $labelClass }}">Password</label>
                        <input type="password" name="password" class="{{ $inputClass }}" required autocomplete="new-password">
                    </div>
                    <div class="md:col-span-1">
                        <label class="{{ $labelClass }}">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="{{ $inputClass }}" required autocomplete="new-password">
                    </div>
                </div>
                <div class="flex shrink-0 flex-wrap justify-end gap-3 border-t border-[#1E3357] bg-[#0D1B31]/40 px-6 py-5 sm:px-8">
                    <button type="button" class="close-reset-modal rounded-2xl border border-[#60A5FA]/25 px-6 py-3 text-sm font-black uppercase text-[#F8FAFC]">Cancel</button>
                    <button type="submit" class="rounded-2xl bg-[#3B82F6] px-6 py-3 text-sm font-black uppercase text-white transition hover:bg-[#2563EB]">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const staffUpdateUrlTemplate = @json(route('admin.settings.staff.update', ['staff' => '__ID__']));
        const staffResetUrlTemplate = @json(route('admin.settings.staff.reset-password', ['staff' => '__ID__']));

        function openModal(modal) {
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(modal) {
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        const createStaffModal = document.getElementById('createStaffModal');
        const editStaffModal = document.getElementById('editStaffModal');
        const resetStaffModal = document.getElementById('resetStaffModal');

        document.getElementById('openCreateStaffModal')?.addEventListener('click', () => openModal(createStaffModal));
        document.querySelectorAll('.close-staff-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                closeModal(createStaffModal);
                closeModal(editStaffModal);
            });
        });
        document.querySelectorAll('.close-reset-modal').forEach(btn => {
            btn.addEventListener('click', () => closeModal(resetStaffModal));
        });

        document.querySelectorAll('.edit-staff-btn').forEach(button => {
            button.addEventListener('click', () => {
                const staff = JSON.parse(button.dataset.staff || '{}');
                const form = document.getElementById('editStaffForm');
                if (!form) return;

                form.action = staffUpdateUrlTemplate.replace('__ID__', String(staff.id));
                form.querySelector('[name="full_name"]').value = staff.full_name || '';
                form.querySelector('[name="email"]').value = staff.email || '';
                form.querySelector('[name="role"]').value = staff.role || 'staff';
                form.querySelector('[name="account_status"]').value = staff.account_status || 'active';

                openModal(editStaffModal);
            });
        });

        document.querySelectorAll('.reset-staff-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.staffId;
                const name = button.dataset.staffName || 'Staff';
                const form = document.getElementById('resetStaffForm');
                const subtitle = document.getElementById('resetStaffSubtitle');
                if (!form) return;

                form.action = staffResetUrlTemplate.replace('__ID__', String(id));
                if (subtitle) subtitle.textContent = 'Set a new password for ' + name + '.';
                openModal(resetStaffModal);
            });
        });

        @if ($activeTab === 'accounts' && ($errors->has('full_name') || $errors->has('email') || $errors->has('password') || $errors->has('role')))
            openModal(document.getElementById(@json(old('_method') === 'PUT' ? 'editStaffModal' : 'createStaffModal')));
        @endif
    </script>
@endif
@endsection
