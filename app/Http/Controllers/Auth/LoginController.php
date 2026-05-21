<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
  public function create()
  {
    return view('admin-login');
  }

  public function store(LoginRequest $request): RedirectResponse
  {
    $email = strtolower(trim((string) $request->input('email')));
    $password = (string) $request->input('password');

    $portalUser = User::query()
      ->where('email', $email)
      ->whereIn('role', User::portalRoles())
      ->first();

    if ($portalUser === null) {
      throw ValidationException::withMessages([
        'email' => 'The provided credentials are invalid.',
      ]);
    }

    if (Schema::hasColumn('users', 'account_status') && ! $portalUser->isActive()) {
      throw ValidationException::withMessages([
        'email' => 'Your account is inactive. Please contact an administrator.',
      ]);
    }

    $passwordValid = Hash::check($password, (string) $portalUser->password);

    if (! $passwordValid) {
      $hasPlainTextPassword = password_get_info((string) $portalUser->password)['algo'] === null;
      $matchesLegacyPassword = $hasPlainTextPassword
        && hash_equals((string) $portalUser->password, $password);

      if (! $matchesLegacyPassword) {
        throw ValidationException::withMessages([
          'email' => 'The provided credentials are invalid.',
        ]);
      }

      $portalUser->forceFill([
        'password' => Hash::make($password),
      ])->save();
    }

    Auth::login($portalUser);
    $request->session()->regenerate();

    if (Schema::hasColumn('users', 'last_login_at')) {
      $portalUser->forceFill([
        'last_login_at' => now(),
      ])->save();
    }

    ActivityLogger::log(
      action: 'User Logged In',
      module: 'Authentication',
      description: sprintf('%s (%s) logged in.', $portalUser->fullName(), $portalUser->email),
      user: $portalUser,
      request: $request,
    );

    $homeRoute = $portalUser->canAccessDashboard()
      ? route('admin.dashboard')
      : route('admin.events');

    return redirect()->intended($homeRoute);
  }

  public function destroy(Request $request): RedirectResponse
  {
    $user = $request->user();

    if ($user !== null) {
      ActivityLogger::log(
        action: 'User Logged Out',
        module: 'Authentication',
        description: sprintf('%s (%s) logged out.', $user->fullName(), $user->email),
        user: $user,
        request: $request,
      );
    }

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('admin.login');
  }
}
