<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "Migrating existing student_registrations to users table...\n";
echo "==========================================================\n\n";

// Get all student registrations that don't have a user account
$students = DB::table('student_registrations')
    ->whereNotIn('email', function($query) {
        $query->select('email')->from('users');
    })
    ->get();

echo "Found {$students->count()} students to migrate\n\n";

$migrated = 0;
$errors = 0;

foreach ($students as $student) {
    try {
        DB::table('users')->insert([
            'name' => strtolower($student->fname . '.' . $student->lname),
            'firstname' => strtoupper($student->fname),
            'lastname' => strtoupper($student->lname),
            'middlename' => $student->mname ? strtoupper($student->mname) : null,
            'suffix_name' => $student->extension ? strtoupper($student->extension) : null,
            'email' => $student->email,
            'password' => $student->password, // Already hashed
            'role' => $student->account_type === 'psg_officer' ? 'officer' : 'student',
            'status' => $student->status === '1' ? 'active' : 'inactive',
            'employee_id' => $student->account_type === 'psg_officer' ? $student->student_id : null,
            'password_changed_at' => now(),
            'force_password_change' => false,
            'created_at' => $student->created_at ?? now(),
            'updated_at' => $student->updated_at ?? now(),
        ]);

        $migrated++;
        echo "✓ Migrated: {$student->email} ({$student->account_type})\n";

    } catch (Exception $e) {
        $errors++;
        echo "✗ Error migrating {$student->email}: " . $e->getMessage() . "\n";
    }
}

echo "\n==========================================================\n";echo "Migration complete!\n";
echo "Migrated: {$migrated} students\n";
echo "Errors: {$errors}\n";
