<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking user data distribution:\n";
echo "=================================\n\n";

$usersCount = DB::table('users')->count();
$studentRegCount = DB::table('student_registrations')->count();
$roleAccountCount = DB::table('role_account')->count();

echo "users table: {$usersCount} records\n";
echo "student_registrations table: {$studentRegCount} records\n";
echo "role_account table: {$roleAccountCount} records\n\n";

if ($studentRegCount > 0) {
    echo "Sample student_registrations records:\n";
    $samples = DB::table('student_registrations')->limit(3)->get(['email', 'account_type', 'status']);
    foreach ($samples as $sample) {
        echo "  - {$sample->email} ({$sample->account_type}, status: {$sample->status})\n";
    }
}

echo "\n";

if ($usersCount > 0) {
    echo "Sample users table records:\n";
    $samples = DB::table('users')->limit(3)->get(['email', 'role', 'status']);
    foreach ($samples as $sample) {
        echo "  - {$sample->email} ({$sample->role}, status: {$sample->status})\n";
    }
}
