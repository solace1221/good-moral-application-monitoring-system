<?php

if (!function_exists('checkRole')) {
    /**
     * Check if the authenticated user has one of the required roles.
     *
     * @param array|string $roles
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    function checkRole($roles)
    {
        // If $roles is a string, convert it to an array
        if (is_string($roles)) {
            $roles = [$roles];
        }

        // Check if the authenticated user has one of the allowed roles
        if (auth()->check() && !in_array(auth()->user()->account_type, $roles)) {
            abort(403, 'Unauthorized access.');
        }
    }
}
