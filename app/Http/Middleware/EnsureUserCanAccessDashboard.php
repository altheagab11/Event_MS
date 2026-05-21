<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanAccessDashboard
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('admin.login');
        }

        if (! $user->canAccessDashboard()) {
            return redirect()
                ->route('admin.events')
                ->withErrors(['access' => 'You do not have permission to access that page.']);
        }

        return $next($request);
    }
}
