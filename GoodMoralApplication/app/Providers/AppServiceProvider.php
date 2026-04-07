<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set up global error handler to catch getimagesize errors
        set_error_handler(function($severity, $message, $file, $line) {
            if (strpos($message, 'getimagesize') !== false) {
                Log::error('getimagesize error caught by global handler', [
                    'message' => $message,
                    'file' => $file,
                    'line' => $line,
                    'severity' => $severity,
                    'stack_trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 15)
                ]);

                // Return true to prevent the error from being displayed
                return true;
            }

            // Return false to let other errors be handled normally
            return false;
        });
    }
}
