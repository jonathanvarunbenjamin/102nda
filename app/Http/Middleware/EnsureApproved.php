<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gate that keeps unapproved members out of the private site.
 * Approved members pass through; pending members are sent to the waiting room;
 * blocked members are logged out.
 */
class EnsureApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isBlocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Your access has been disabled. Please contact an administrator.');
        }

        if (! $user->isApproved()) {
            return redirect()->route('pending');
        }

        return $next($request);
    }
}
