<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckModeratorRoleExceptProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Skip role check for profile route
        if ($request->is('sec_osa/profile')) {
            Log::info('Role check bypassed for profile page', [
                'path' => $request->path(),
                'user' => Auth::check() ? Auth::user()->account_type : 'unauthenticated'
            ]);
            return $next($request);
        }
        
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Get user account type
        $accountType = Auth::user()->account_type;
        
        // Check if user has one of the allowed roles
        if (!in_array($accountType, $roles)) {
            Log::warning('Unauthorized access attempt via middleware', [
                'path' => $request->path(),
                'user_type' => $accountType,
                'required_roles' => $roles
            ]);
            abort(403, 'Unauthorized access. Access denied for your account type.');
        }
        
        return $next($request);
    }
}