<?php
/**
 * Test Data Sync Between CMS and GMAMS
 * 
 * This script verifies that user records created in one system appear in the other.
 * Run this after creating a test user in CMS.
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "===================================\n";
echo "DATA SYNC VERIFICATION TEST\n";
echo "===================================\n\n";

// Test 1: Check if tables exist
echo "1. Checking tables...\n";
$tables = ['users', 'students', 'student_registrations'];
foreach ($tables as $table) {
    $exists = DB::select("SHOW TABLES LIKE '$table'");
    echo "   - $table: " . ($exists ? "✓ EXISTS" : "✗ MISSING") . "\n";
}

echo "\n2. Checking CMS students...\n";
$cmsStudents = DB::table('students')
    ->join('users', 'students.users_id', '=', 'users.id')
    ->select('users.email', 'users.firstname', 'users.lastname', 'students.student_number', 'users.created_at')
    ->orderBy('users.created_at', 'desc')
    ->limit(5)
    ->get();

if ($cmsStudents->isEmpty()) {
    echo "   ✗ No students found in CMS\n";
} else {
    echo "   ✓ Found " . count($cmsStudents) . " students (showing latest 5):\n";
    foreach ($cmsStudents as $student) {
        echo "     - {$student->email} ({$student->firstname} {$student->lastname}) - ID: {$student->student_number}\n";
    }
}

echo "\n3. Checking GMAMS student_registrations...\n";
$gmamsStudents = DB::table('student_registrations')
    ->select('email', 'fname', 'lname', 'student_id', 'created_at')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

if ($gmamsStudents->isEmpty()) {
    echo "   ✗ No students found in GMAMS\n";
} else {
    echo "   ✓ Found " . count($gmamsStudents) . " students (showing latest 5):\n";
    foreach ($gmamsStudents as $student) {
        echo "     - {$student->email} ({$student->fname} {$student->lname}) - ID: {$student->student_id}\n";
    }
}

echo "\n4. Cross-checking sync...\n";
if (!$cmsStudents->isEmpty() && !$gmamsStudents->isEmpty()) {
    $cmsEmails = $cmsStudents->pluck('email')->toArray();
    $gmamsEmails = $gmamsStudents->pluck('email')->toArray();
    
    $synced = array_intersect($cmsEmails, $gmamsEmails);
    $missingInGmams = array_diff($cmsEmails, $gmamsEmails);
    
    echo "   - Synced students: " . count($synced) . "\n";
    if (count($synced) > 0) {
        echo "     ✓ " . implode(', ', $synced) . "\n";
    }
    
    if (count($missingInGmams) > 0) {
        echo "   - Missing in GMAMS: " . count($missingInGmams) . "\n";
        echo "     ✗ " . implode(', ', $missingInGmams) . "\n";
    } else {
        echo "   ✓ All CMS students are synced to GMAMS\n";
    }
}

echo "\n===================================\n";
echo "TEST COMPLETE\n";
echo "===================================\n";
