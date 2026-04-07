<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request for admin-only routes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Authentication required.');
        }

        if (Auth::user()->account_type !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }

        if (Auth::user()->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Account is inactive.');
        }

        return $next($request);
    }
}
