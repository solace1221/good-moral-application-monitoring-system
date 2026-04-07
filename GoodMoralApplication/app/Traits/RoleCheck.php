<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait RoleCheck
{
    /**
     * Check if the authenticated user has one of the required roles.
     *
     * @param array|string $roles
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function checkRole($roles)
    {
        // Log role check attempt
        Log::info('Role check attempted', [
            'path' => request()->path(),
            'user' => Auth::check() ? Auth::user()->account_type : 'unauthenticated',
            'roles' => $roles
        ]);
        
        // If $roles is a string, convert it to an array
        if (is_string($roles)) {
            $roles = [$roles];
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            abort(401, 'Unauthenticated. Please log in first.');
        }

        // Check if the authenticated user has one of the allowed roles
        if (!in_array(Auth::user()->account_type, $roles)) {
            Log::warning('Unauthorized access attempt', [
                'path' => request()->path(),
                'user_type' => Auth::user()->account_type,
                'required_roles' => $roles
            ]);
            abort(403, 'Unauthorized access. Access denied for your account type.');
        }
    }
}
