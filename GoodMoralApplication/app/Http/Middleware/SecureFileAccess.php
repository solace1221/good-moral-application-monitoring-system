<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SecureFileAccess
{
    /**
     * Handle an incoming request to validate file access permissions.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->route('path');
        
        // Prevent directory traversal attacks
        if (strpos($path, '..') !== false || strpos($path, './') !== false) {
            abort(403, 'Invalid file path.');
        }

        // Validate file exists and is in allowed directories
        $allowedPaths = [
            'uploaded_receipts/',
            'payment_notices/',
            'violations_documents/',
            'proceedings_documents/'
        ];

        $isAllowed = false;
        foreach ($allowedPaths as $allowedPath) {
            if (strpos($path, $allowedPath) === 0) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            abort(403, 'Access to this file is not permitted.');
        }

        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) {
            abort(404, 'File not found.');
        }

        // Validate file type
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        
        if (!in_array($extension, $allowedExtensions)) {
            abort(403, 'File type not allowed.');
        }

        return $next($request);
    }
}
