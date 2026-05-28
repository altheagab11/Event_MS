<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()
            ->where('email', strtolower(trim((string) $validated['email'])))
            ->whereIn('role', User::portalRoles())
            ->first();

        if ($user === null) {
            return back()
                ->withErrors(['email' => 'We could not find a portal account for that email address.'])
                ->onlyInput('email');
        }

        $status = Password::sendResetLink([
            'email' => $user->email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            return back()
                ->withErrors(['email' => __($status)])
                ->onlyInput('email');
        }

        return back()->with('status', __($status));
    }
}
