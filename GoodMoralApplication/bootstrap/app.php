<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'secure.file' => \App\Http\Middleware\SecureFileAccess::class,
        ]);

        // Redirect authenticated users based on role (guest middleware)
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            $user = $request->user();
            if (!$user) {
                return '/dashboard';
            }

            return match ($user->account_type) {
                'admin' => '/admin/dashboard',
                'dean' => '/dean/dashboard',
                'sec_osa' => '/sec_osa/dashboard',
                'psg_officer' => '/PsgOfficer/dashboard',
                'registrar' => '/registrar/goodMoralApplication',
                'prog_coor' => '/prog_coor/major',
                default => '/dashboard',
            };
        });

        // Add security headers middleware
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
