<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
  /**
   * Show the login form.
   */
  public function create()
  {
    return view('admin-login');
  }

  /**
   * Handle an incoming authentication request.
   */
  public function store(LoginRequest $request): RedirectResponse
  {
    $email = strtolower(trim((string) $request->input('email')));
    $password = (string) $request->input('password');
    $credentials = [
      'email' => $email,
      'password' => $password,
      'role' => 'admin',
    ];

    if (! Auth::attempt($credentials)) {
      $adminUser = User::query()
        ->where('email', $email)
        ->where('role', 'admin')
        ->first();

      $hasPlainTextPassword = $adminUser !== null
        && password_get_info((string) $adminUser->password)['algo'] === null;
      $matchesLegacyPassword = $hasPlainTextPassword
        && hash_equals((string) $adminUser->password, $password);

      if (! $matchesLegacyPassword) {
        throw ValidationException::withMessages([
          'email' => 'The provided credentials are invalid.',
        ]);
      }

      Auth::login($adminUser);

      $adminUser->forceFill([
        'password' => Hash::make($password),
      ])->save();
    }

    $request->session()->regenerate();

    if (Schema::hasColumn('users', 'last_login_at') && $request->user() !== null) {
      $request->user()->forceFill([
        'last_login_at' => now(),
      ])->save();
    }

    return redirect()->intended(route('admin.dashboard'));
  }

  /**
   * Destroy an authenticated session.
   */
  public function destroy(Request $request): RedirectResponse
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('admin.login');
  }
}
