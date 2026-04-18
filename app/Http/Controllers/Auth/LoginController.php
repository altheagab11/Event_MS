<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
  /**
   * Show the login form.
   */
  public function create()
  {
    if (Auth::check()) {
      return redirect()->route('admin.dashboard');
    }

    return view('admin-login');
  }

  /**
   * Handle an incoming authentication request.
   */
  public function store(LoginRequest $request): RedirectResponse
  {
    $credentials = $request->only('email', 'password');

    if (! Auth::attempt($credentials)) {
      throw ValidationException::withMessages([
        'email' => 'The provided credentials are invalid.',
      ]);
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
