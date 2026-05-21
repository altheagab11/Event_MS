<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAdminProfileRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminSettingsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $isSuperAdmin = $user->canManageAccounts();

        $activeTab = (string) $request->query('tab', 'profile');
        $allowedTabs = ['profile'];

        if ($isSuperAdmin) {
            $allowedTabs = ['profile', 'accounts', 'roles', 'logs'];
        }

        if (! in_array($activeTab, $allowedTabs, true)) {
            $activeTab = 'profile';
        }

        $staffAccounts = collect();
        $activityLogs = collect();
        $logModules = collect();
        $logRoles = collect();

        if ($isSuperAdmin) {
            $staffAccounts = User::query()
                ->whereIn('role', User::portalRoles())
                ->orderByRaw("CASE role WHEN 'super_admin' THEN 0 WHEN 'admin' THEN 1 ELSE 2 END")
                ->orderBy('firstname')
                ->orderBy('lastname')
                ->get()
                ->map(fn (User $account): array => [
                    'id' => $account->id,
                    'full_name' => $account->fullName(),
                    'email' => $account->email,
                    'role' => $account->role,
                    'role_label' => $account->roleLabel(),
                    'account_status' => $account->account_status ?? User::STATUS_ACTIVE,
                    'status_label' => ucfirst((string) ($account->account_status ?? User::STATUS_ACTIVE)),
                    'initials' => $account->initials(),
                    'is_self' => (int) $account->id === (int) $user->id,
                    'is_super_admin' => $account->isSuperAdmin(),
                ]);

            if ($activeTab === 'logs') {
                $logsQuery = ActivityLog::query()
                    ->with('user:id,firstname,lastname,email,role')
                    ->orderByDesc('created_at')
                    ->orderByDesc('log_id');

                if ($request->filled('log_role')) {
                    $logsQuery->where('user_role', $request->string('log_role')->toString());
                }

                if ($request->filled('log_module')) {
                    $logsQuery->where('module', $request->string('log_module')->toString());
                }

                if ($request->filled('log_search')) {
                    $search = '%'.$request->string('log_search')->toString().'%';
                    $logsQuery->where(function ($query) use ($search): void {
                        $query->where('action', 'like', $search)
                            ->orWhere('module', 'like', $search)
                            ->orWhere('description', 'like', $search)
                            ->orWhereHas('user', function ($userQuery) use ($search): void {
                                $userQuery->where('firstname', 'like', $search)
                                    ->orWhere('lastname', 'like', $search)
                                    ->orWhere('email', 'like', $search);
                            });
                    });
                }

                $activityLogs = $logsQuery
                    ->paginate(25)
                    ->withQueryString()
                    ->through(fn (ActivityLog $log): array => [
                        'log_id' => $log->log_id,
                        'created_at' => $log->created_at?->format('M j, Y g:i A') ?? '—',
                        'user_name' => $log->userDisplayName(),
                        'user_role' => $log->roleLabel(),
                        'action' => $log->action,
                        'module' => $log->module ?? '—',
                        'description' => $log->description ?? '—',
                        'ip_address' => $log->ip_address ?? '—',
                    ]);

                $logModules = ActivityLog::query()
                    ->whereNotNull('module')
                    ->distinct()
                    ->orderBy('module')
                    ->pluck('module');

                $logRoles = ActivityLog::query()
                    ->whereNotNull('user_role')
                    ->distinct()
                    ->orderBy('user_role')
                    ->pluck('user_role');
            }
        }

        return view('admin.settings.index', [
            'activeTab' => $activeTab,
            'isSuperAdmin' => $isSuperAdmin,
            'profile' => [
                'full_name' => $user->fullName(),
                'email' => $user->email,
                'role' => $user->role,
                'role_label' => $user->roleLabel(),
                'account_status' => $user->account_status ?? User::STATUS_ACTIVE,
            ],
            'staffAccounts' => $staffAccounts,
            'permissionMatrix' => $this->permissionMatrix(),
            'activityLogs' => $activityLogs,
            'logModules' => $logModules,
            'logRoles' => $logRoles,
            'logFilters' => [
                'log_role' => $request->string('log_role')->toString(),
                'log_module' => $request->string('log_module')->toString(),
                'log_search' => $request->string('log_search')->toString(),
            ],
        ]);
    }

    public function updateProfile(UpdateAdminProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $attributes = User::splitFullName($request->string('full_name')->toString(), [
            'email' => strtolower(trim($request->string('email')->toString())),
        ]);

        if ($request->filled('password')) {
            $attributes['password'] = Hash::make($request->string('password')->toString());
        }

        $user->update($attributes);

        return redirect()
            ->route('admin.settings', ['tab' => 'profile'])
            ->with('status', 'Profile updated successfully.');
    }

    /**
     * @return array<int, array{feature: string, super_admin: bool, admin: bool, staff: bool}>
     */
    private function permissionMatrix(): array
    {
        return [
            ['feature' => 'Dashboard', 'super_admin' => true, 'admin' => true, 'staff' => false],
            ['feature' => 'Events', 'super_admin' => true, 'admin' => true, 'staff' => true],
            ['feature' => 'Participants', 'super_admin' => true, 'admin' => true, 'staff' => true],
            ['feature' => 'Evaluations', 'super_admin' => true, 'admin' => true, 'staff' => true],
            ['feature' => 'Settings', 'super_admin' => true, 'admin' => true, 'staff' => true],
            ['feature' => 'Profile Settings', 'super_admin' => true, 'admin' => true, 'staff' => true],
            ['feature' => 'Account Management', 'super_admin' => true, 'admin' => false, 'staff' => false],
            ['feature' => 'Create Account', 'super_admin' => true, 'admin' => false, 'staff' => false],
            ['feature' => 'Role & Permissions', 'super_admin' => true, 'admin' => false, 'staff' => false],
            ['feature' => 'Activity Logs', 'super_admin' => true, 'admin' => false, 'staff' => false],
        ];
    }
}
