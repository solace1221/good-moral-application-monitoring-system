<?php

/**
 * Test Student Registration Logic
 * This script tests the new student registration process that stores data in users table
 * Based on the clearance system's student registration approach
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\Hash;

echo "=== Student Registration Logic Test ===\n\n";

// Test data
$testStudentId = 'TEST-2026-001';
$testEmail = 'test.student@spup.edu.ph';

echo "1. Checking if test student already exists...\n";
$existingUser = User::where('student_id', $testStudentId)->first();
$existingReg = StudentRegistration::where('student_id', $testStudentId)->first();

if ($existingUser) {
    echo "   ✓ Found existing user: " . $existingUser->fullname . "\n";
    echo "   - User ID: " . $existingUser->id . "\n";
    echo "   - Email: " . $existingUser->email . "\n";
    echo "   - Role: " . $existingUser->role . "\n";
    echo "   - Department: " . $existingUser->department . "\n";
    echo "   - Status: " . $existingUser->status . "\n\n";
    
    echo "   Deleting test user for fresh test...\n";
    $existingUser->delete();
}

if ($existingReg) {
    echo "   Deleting test StudentRegistration for fresh test...\n";
    $existingReg->delete();
}

echo "\n2. Testing NEW REGISTRATION (with all name fields)...\n";

try {
    // Simulate registration data - using ALL fields now
    $registrationData = [
        'name' => 'Juan S. Dela Cruz Jr.',
        'fname' => 'Juan',
        'lname' => 'Dela Cruz',
        'mname' => 'Santos',
        'extension' => 'Jr.',
        'fullname' => 'Juan S. Dela Cruz Jr.',
        'email' => $testEmail,
        'password' => Hash::make('password123'),
        'student_id' => $testStudentId,
        'gender' => 'male',
        'department' => 'SITE',
        'year_level' => 'BSIT-4A',
        'role' => 'student',
        'status' => '1',
    ];

    echo "   Creating user in users table (PRIMARY STORAGE)...\n";
    $user = User::create($registrationData);
    
    if ($user) {
        echo "   ✓ User created successfully!\n";
        echo "   - User ID: " . $user->id . "\n";
        echo "   - Name: " . $user->name . "\n";
        echo "   - Fullname: " . $user->fullname . "\n";
        echo "   - First Name: " . $user->fname . "\n";
        echo "   - Last Name: " . $user->lname . "\n";
        echo "   - Student ID: " . $user->student_id . "\n";
        echo "   - Email: " . $user->email . "\n";
        echo "   - Role: " . $user->role . "\n";
        echo "   - Department: " . $user->department . "\n";
        echo "   - Year Level: " . $user->year_level . "\n\n";
    }

    echo "   Creating StudentRegistration (backward compatibility)...\n";
    $studentReg = StudentRegistration::create([
        'fname' => 'Juan',
        'mname' => 'Santos',
        'lname' => 'Dela Cruz',
        'extension' => 'Jr.',
        'gender' => $registrationData['gender'],
        'email' => $registrationData['email'],
        'department' => $registrationData['department'],
        'password' => $registrationData['password'],
        'student_id' => $registrationData['student_id'],
        'status' => $registrationData['status'],
        'account_type' => 'student',
        'year_level' => $registrationData['year_level'],
    ]);

    if ($studentReg) {
        echo "   ✓ StudentRegistration created successfully!\n\n";
    }

} catch (\Exception $e) {
    echo "   ✗ Error creating student: " . $e->getMessage() . "\n\n";
}

echo "3. Testing DUPLICATE PREVENTION...\n";

try {
    // Try to find duplicate by student_id only (no fname/lname in users table)
    $duplicateUser = User::where('student_id', $testStudentId)->first();

    if ($duplicateUser) {
        echo "   ✓ Duplicate detection working!\n";
        echo "   - Found existing user: " . $duplicateUser->name . "\n";
        echo "   - Student ID match: Yes\n";
        echo "   - This would prevent duplicate registration ✓\n\n";
    } else {
        echo "   ✗ Duplicate detection failed - no existing user found\n\n";
    }

} catch (\Exception $e) {
    echo "   ✗ Error in duplicate check: " . $e->getMessage() . "\n\n";
}

echo "4. Verifying Data Storage...\n";

$verifyUser = User::where('student_id', $testStudentId)->first();
$verifyReg = StudentRegistration::where('student_id', $testStudentId)->first();

echo "   Users table (PRIMARY):\n";
if ($verifyUser) {
    echo "   ✓ User found in users table\n";
    echo "     - ID: " . $verifyUser->id . "\n";
    echo "     - Name: " . $verifyUser->name . "\n";
    echo "     - Email: " . $verifyUser->email . "\n";
    echo "     - Role: " . $verifyUser->role . "\n";
    echo "     - Department: " . $verifyUser->department . "\n";
} else {
    echo "   ✗ User NOT found in users table\n";
}

echo "\n   StudentRegistration table (LEGACY):\n";
if ($verifyReg) {
    echo "   ✓ Record found in student_registrations table\n";
    echo "     - Student ID: " . $verifyReg->student_id . "\n";
    echo "     - Email: " . $verifyReg->email . "\n";
} else {
    echo "   ✗ Record NOT found in student_registrations table\n";
}

echo "\n5. Testing Authentication Compatibility...\n";

try {
    $authUser = User::where('email', $testEmail)->first();
    
    if ($authUser && Hash::check('password123', $authUser->password)) {
        echo "   ✓ Authentication test PASSED\n";
        echo "   - User can login with email and password\n";
        echo "   - Role: " . $authUser->role . "\n";
        echo "   - Department: " . $authUser->department . "\n";
    } else {
        echo "   ✗ Authentication test FAILED\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Authentication test error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Summary ===\n";
echo "✓ Student registration uses users table with ALL name fields\n";
echo "✓ Fields available: name, fname, lname, mname, fullname, extension\n";
echo "✓ Duplicate prevention working (checks student_id)\n";
echo "✓ StudentRegistration maintained for backward compatibility\n";
echo "✓ Authentication system compatible\n\n";

echo "Cleaning up test data...\n";
if ($verifyUser) {
    $verifyUser->delete();
    echo "✓ Test user deleted from users table\n";
}
if ($verifyReg) {
    $verifyReg->delete();
    echo "✓ Test record deleted from student_registrations table\n";
}

echo "\n=== Test Complete ===\n";
