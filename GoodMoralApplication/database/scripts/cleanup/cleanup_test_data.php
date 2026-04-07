<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Cleaning up test data...\n";

// Delete clearances first (foreign key)
$clearances = DB::table('clearances')
    ->whereIn('student_id', function($query) {
        $query->select('id')
            ->from('students')
            ->whereIn('student_number', ['TEST-2024-001', 'TEST-2020-001']);
    })
    ->delete();
echo "Deleted {$clearances} clearance records\n";

// Delete students
$students = DB::table('students')
    ->whereIn('student_number', ['TEST-2024-001', 'TEST-2020-001'])
    ->delete();
echo "Deleted {$students} student records\n";

// Delete users
$users = DB::table('users')
    ->whereIn('email', ['test.sync@spup.edu.ph', 'test.alumni@spup.edu.ph', 'test.officer@spup.edu.ph'])
    ->delete();
echo "Deleted {$users} user records\n";

echo "\nCleanup complete!\n";
