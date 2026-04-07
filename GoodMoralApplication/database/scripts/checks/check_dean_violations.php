<?php
/**
 * Quick diagnostic script to check dean violations data
 * Run: php check_dean_violations.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\StudentViolation;
use App\Models\User;

echo "=== DEAN VIOLATIONS DIAGNOSTIC ===\n\n";

// Check total violations in database
$totalViolations = StudentViolation::count();
echo "Total violations in database: $totalViolations\n\n";

// Check violations by type
$minorCount = StudentViolation::where('offense_type', 'minor')->count();
$majorCount = StudentViolation::where('offense_type', 'major')->count();
echo "Minor violations: $minorCount\n";
echo "Major violations: $majorCount\n\n";

// Check violations by department
echo "=== Violations by Department ===\n";
$departments = StudentViolation::select('department')->distinct()->pluck('department');
foreach ($departments as $dept) {
    $count = StudentViolation::where('department', $dept)->count();
    $minorDept = StudentViolation::where('department', $dept)->where('offense_type', 'minor')->count();
    $majorDept = StudentViolation::where('department', $dept)->where('offense_type', 'major')->count();
    echo "$dept: $count total ($minorDept minor, $majorDept major)\n";
}
echo "\n";

// Check dean accounts in users table
echo "=== Dean Accounts (from users table) ===\n";
$deans = User::where('role', 'dean')->get();
foreach ($deans as $dean) {
    echo "Email: {$dean->email} | Role: {$dean->role} | Department Code: {$dean->department}\n";
    
    // Check violations for this dean's department
    if ($dean->department) {
        // Map department abbreviations to possible full names
        $departmentMap = [
          'SITE' => ['SITE', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING'],
          'SNAHS' => ['SNAHS', 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES'],
          'SBAHM' => ['SBAHM', 'SCHOOL OF BUSINESS ADMINISTRATION AND HOSPITALITY MANAGEMENT'],
          'SASTE' => ['SASTE', 'SCHOOL OF ARTS, SCIENCES, TEACHER EDUCATION'],
          'SOM' => ['SOM', 'SCHOOL OF MEDICINE'],
          'GRADSCH' => ['GRADSCH', 'GRADUATE SCHOOL'],
        ];
        
        $possibleDepts = $departmentMap[$dean->department] ?? [$dean->department];
        
        $deanMinor = StudentViolation::whereIn('department', $possibleDepts)
            ->where('offense_type', 'minor')->count();
        $deanMajor = StudentViolation::whereIn('department', $possibleDepts)
            ->where('offense_type', 'major')->count();
        echo "  -> Violations accessible: $deanMinor minor, $deanMajor major\n";
    }
}
echo "\n";

// Show sample violations
echo "=== Sample Violations (First 5) ===\n";
$samples = StudentViolation::take(5)->get();
foreach ($samples as $violation) {
    echo "ID: {$violation->id} | Student: {$violation->student_id} | Type: {$violation->offense_type} | Dept: {$violation->department} | Violation: " . substr($violation->violation, 0, 50) . "...\n";
}
echo "\n";

echo "=== DIAGNOSTIC COMPLETE ===\n";
