<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RoleAccount;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();
        
        // For student-only routes, use role_account.account_type as the authoritative source
        // so that alumni who still have users.role='student' (legacy data) are correctly blocked.
        $effectiveRole = $user->account_type; // virtual accessor → users.role
        if (in_array('student', $roles) && $effectiveRole === 'student') {
            $profile = RoleAccount::where('email', $user->email)->first();
            if ($profile && $profile->account_type !== 'student') {
                abort(403, 'Unauthorized. You do not have permission to access this resource.');
            }
        }

        // Check if user's account type matches any of the allowed roles
        if (!in_array($effectiveRole, $roles)) {
            abort(403, 'Unauthorized. You do not have permission to access this resource.');
        }

        // Additional security: Check if account is active
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        return $next($request);
    }
}
