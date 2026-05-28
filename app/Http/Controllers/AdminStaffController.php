<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetStaffPasswordRequest;
use App\Http\Requests\StoreStaffAccountRequest;
use App\Http\Requests\UpdateStaffAccountRequest;
use App\Mail\PortalAccountCreatedMail;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AdminStaffController extends Controller
{
    private const OWN_SUPER_ADMIN_BLOCKED_MESSAGE = 'You cannot deactivate, delete, or change the role of your own Super Admin account.';

    public function store(StoreStaffAccountRequest $request): RedirectResponse
    {
        $temporaryPassword = Str::random(12);

        $attributes = User::splitFullName($request->string('full_name')->toString(), [
            'email' => strtolower(trim($request->string('email')->toString())),
            'password' => $temporaryPassword,
            'role' => $request->string('role')->toString(),
            'account_status' => $request->string('account_status')->toString(),
            'email_verified_at' => now(),
        ]);

        $account = User::query()->create($attributes);
        $resetToken = Password::broker()->createToken($account);
        $resetUrl = route('password.reset', [
            'token' => $resetToken,
            'email' => $account->email,
        ]);

        Mail::to($account->email)->send(new PortalAccountCreatedMail(
            fullName: $account->fullName(),
            roleLabel: $account->roleLabel(),
            email: $account->email,
            temporaryPassword: $temporaryPassword,
            resetUrl: $resetUrl
        ));

        ActivityLogger::log(
            action: 'Account Created',
            module: 'Account Management',
            description: sprintf(
                'Super Admin created %s account for %s (%s) and sent credentials via email.',
                $account->roleLabel(),
                $account->fullName(),
                $account->email
            ),
        );

        return redirect()
            ->route('admin.settings', ['tab' => 'accounts'])
            ->with('status', 'Account created successfully. Temporary credentials and reset instructions were sent via email.');
    }

    public function update(UpdateStaffAccountRequest $request, User $staff): RedirectResponse
    {
        $this->ensureManageableAccount($staff);

        if ($response = $this->denyOwnSuperAdminDangerousAction($staff)) {
            return $response;
        }

        if ($staff->isSuperAdmin()) {
            return back()->withErrors([
                'staff' => 'Super Admin accounts cannot be edited here. Use Profile Settings for your own account.',
            ]);
        }

        $this->guardSuperAdminRoleChange($request, $staff);

        $attributes = User::splitFullName($request->string('full_name')->toString(), [
            'email' => strtolower(trim($request->string('email')->toString())),
            'role' => $request->string('role')->toString(),
            'account_status' => $request->string('account_status')->toString(),
        ]);

        if ($request->filled('password')) {
            $attributes['password'] = $request->string('password')->toString();
        }

        $staff->update($attributes);

        ActivityLogger::log(
            action: 'Account Edited',
            module: 'Account Management',
            description: sprintf(
                'Super Admin edited account for %s (%s). Role: %s, Status: %s.',
                $staff->fullName(),
                $staff->email,
                $staff->roleLabel(),
                ucfirst((string) ($staff->account_status ?? User::STATUS_ACTIVE))
            ),
        );

        return redirect()
            ->route('admin.settings', ['tab' => 'accounts'])
            ->with('status', 'Account updated successfully.');
    }

    public function deactivate(User $staff): RedirectResponse
    {
        $this->ensureManageableAccount($staff);

        if ($response = $this->denyOwnSuperAdminDangerousAction($staff)) {
            return $response;
        }

        if ($staff->isSuperAdmin() && $this->activeSuperAdminCount() <= 1) {
            return back()->withErrors(['staff' => 'At least one active Super Admin account is required.']);
        }

        if ($staff->isAdmin() && $this->activeAdminCount() <= 1 && $this->activeSuperAdminCount() === 0) {
            return back()->withErrors(['staff' => 'At least one active admin-level account is required.']);
        }

        $staff->update(['account_status' => User::STATUS_INACTIVE]);

        ActivityLogger::log(
            action: 'Account Deactivated',
            module: 'Account Management',
            description: sprintf(
                'Super Admin deactivated account for %s (%s).',
                $staff->fullName(),
                $staff->email
            ),
        );

        return redirect()
            ->route('admin.settings', ['tab' => 'accounts'])
            ->with('status', 'Account deactivated successfully.');
    }

    public function activate(User $staff): RedirectResponse
    {
        $this->ensureManageableAccount($staff);

        if ($response = $this->denyOwnSuperAdminDangerousAction($staff)) {
            return $response;
        }

        $staff->update(['account_status' => User::STATUS_ACTIVE]);

        ActivityLogger::log(
            action: 'Account Activated',
            module: 'Account Management',
            description: sprintf(
                'Super Admin activated account for %s (%s).',
                $staff->fullName(),
                $staff->email
            ),
        );

        return redirect()
            ->route('admin.settings', ['tab' => 'accounts'])
            ->with('status', 'Account activated successfully.');
    }

    public function resetPassword(ResetStaffPasswordRequest $request, User $staff): RedirectResponse
    {
        $this->ensureManageableAccount($staff);

        if ($staff->isSuperAdmin() && (int) $staff->id !== (int) auth()->id()) {
            return back()->withErrors([
                'staff' => 'Password reset is only available for your own Super Admin account or for Admin and Staff accounts.',
            ]);
        }

        $staff->update([
            'password' => $request->string('password')->toString(),
        ]);

        $isSelf = (int) $staff->id === (int) auth()->id();

        ActivityLogger::log(
            action: 'Password Reset',
            module: 'Account Management',
            description: $isSelf
                ? sprintf('Super Admin reset their own password (%s).', $staff->email)
                : sprintf(
                    'Super Admin reset password for %s (%s).',
                    $staff->fullName(),
                    $staff->email
                ),
        );

        return redirect()
            ->route('admin.settings', ['tab' => 'accounts'])
            ->with('status', 'Password reset successfully for '.$staff->fullName().'.');
    }

    private function ensureManageableAccount(User $staff): void
    {
        if (! $staff->canAccessPortal()) {
            abort(404);
        }
    }

    private function denyOwnSuperAdminDangerousAction(User $staff): ?RedirectResponse
    {
        $actor = auth()->user();

        if ($actor === null || ! $actor->isSuperAdmin()) {
            return null;
        }

        if ((int) $staff->id !== (int) $actor->id) {
            return null;
        }

        return back()->withErrors([
            'staff' => self::OWN_SUPER_ADMIN_BLOCKED_MESSAGE,
        ]);
    }

    private function guardSuperAdminRoleChange(UpdateStaffAccountRequest $request, User $staff): void
    {
        if ($request->string('role')->toString() === User::ROLE_SUPER_ADMIN) {
            abort(403, 'Super Admin accounts cannot be assigned from this form.');
        }
    }

    private function activeAdminCount(): int
    {
        return User::query()
            ->where('role', User::ROLE_ADMIN)
            ->where('account_status', User::STATUS_ACTIVE)
            ->count();
    }

    private function activeSuperAdminCount(): int
    {
        return User::query()
            ->where('role', User::ROLE_SUPER_ADMIN)
            ->where('account_status', User::STATUS_ACTIVE)
            ->count();
    }
}
