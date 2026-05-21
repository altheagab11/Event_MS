<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePortalUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->canAccessPortal()) {
            Auth::logout();

            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'You do not have permission to access the admin portal.']);
        }

        return $next($request);
    }
}
