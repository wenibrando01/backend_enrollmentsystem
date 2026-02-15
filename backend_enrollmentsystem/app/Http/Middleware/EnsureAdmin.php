<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     * Redirect non-admins away from admin-only routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user || ! ($user->is_admin ?? false)) {
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
