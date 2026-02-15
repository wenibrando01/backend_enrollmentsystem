<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotAdmin
{
    /**
     * Handle an incoming request.
     * Redirect admins to admin dashboard - students-only routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && ($user->is_admin ?? false)) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
