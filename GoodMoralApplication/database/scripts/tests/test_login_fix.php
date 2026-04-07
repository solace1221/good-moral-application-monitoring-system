<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

try {
    echo "Testing Login Fix\n";
    echo "=================\n\n";

    // Get admin user
    $user = User::where('email', 'admin@spup.edu.ph')->first();

    if (!$user) {
        echo "Admin user not found!\n";
        exit(1);
    }

    echo "User found:\n";
    echo "- Email: {$user->email}\n";
    echo "- Name: {$user->name}\n";
    echo "- Role: {$user->role}\n";
    echo "- Account Type (accessor): {$user->account_type}\n";
    echo "- Status: {$user->status}\n";
    echo "- Fullname: {$user->fullname}\n";
    echo "- Failed Login Attempts: {$user->failed_login_attempts}\n";
    echo "- Force Password Change: {$user->force_password_change}\n";
    echo "\n";

    // Test password
    $testPassword = 'admin123';
    echo "Testing password: $testPassword\n";
    $passwordMatches = Hash::check($testPassword, $user->password);
    echo "Password matches: " . ($passwordMatches ? "YES" : "NO") . "\n\n";

    // Test authentication
    if ($passwordMatches && $user->status == 'active') {
        echo "✓ Login should work!\n";

        // Simulate what would happen in redirectBasedOnRole
        switch ($user->account_type) {
            case 'admin':
                echo "✓ Would redirect to: admin.dashboard\n";
                break;
            default:
                echo "✓ Would redirect to: dashboard\n";
                break;
        }
    } else {
        echo "✗ Login would fail!\n";
        if (!$passwordMatches) {
            echo "  Reason: Password doesn't match\n";
        }
        if ($user->status != 'active') {
            echo "  Reason: Account is not active (status: {$user->status})\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
