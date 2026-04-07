<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "Testing Login Flow\n";
echo "==================\n\n";

// Get a student user
$student = DB::table('users')->where('role', 'student')->first();

if (!$student) {
    echo "No student user found!\n";
    exit(1);
}

echo "Testing with student: {$student->email}\n";
echo "Status: {$student->status}\n";
echo "Role: {$student->role}\n\n";

// Load the User model to check for issues
try {
    $userModel = User::find($student->id);
    echo "✓ User model loaded successfully\n";
    echo "  - ID: {$userModel->id}\n";
    echo "  - Email: {$userModel->email}\n";
    echo "  - Role: {$userModel->role}\n";
    echo "  - Account Type (accessor): {$userModel->account_type}\n";
    echo "  - Name: {$userModel->name}\n";
    echo "  - Firstname: " . ($userModel->firstname ?? 'NULL') . "\n";
    echo "  - Lastname: " . ($userModel->lastname ?? 'NULL') . "\n";

    // Check for missing columns that might cause issues
    $attributes = $userModel->getAttributes();
    echo "\nAll attributes:\n";
    foreach ($attributes as $key => $value) {
        if (!in_array($key, ['password', 'remember_token'])) {
            echo "  - {$key}: " . (is_null($value) ? 'NULL' : substr(strval($value), 0, 50)) . "\n";
        }
    }

} catch (Exception $e) {
    echo "✗ Error loading user model: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
