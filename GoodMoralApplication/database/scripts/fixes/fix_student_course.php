<?php
/**
 * Quick fix script to set course for students who don't have one
 * Run this with: php fix_student_course.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RoleAccount;

echo "=== Student Course Fix Script ===\n\n";

// Find students without course set
$studentsWithoutCourse = RoleAccount::whereIn('account_type', ['student', 'alumni'])
    ->where(function($query) {
        $query->whereNull('course')
              ->orWhere('course', '');
    })
    ->get();

if ($studentsWithoutCourse->isEmpty()) {
    echo "✓ All students already have courses set!\n";
    exit(0);
}

echo "Found " . $studentsWithoutCourse->count() . " student(s) without course:\n\n";

foreach ($studentsWithoutCourse as $student) {
    echo "Student: {$student->fullname} (ID: {$student->student_id})\n";
    echo "Department: {$student->department}\n";
    
    // Suggest course based on department
    $suggestedCourse = null;
    $suggestedYearLevel = '4th Year'; // Default year level
    
    switch ($student->department) {
        case 'SITE':
            $suggestedCourse = 'BSIT';
            break;
        case 'SASTE':
            $suggestedCourse = 'BS Psych';
            break;
        case 'SBAHM':
            $suggestedCourse = 'BSA';
            break;
        case 'SNAHS':
            $suggestedCourse = 'BSN';
            break;
        case 'SOM':
            $suggestedCourse = 'Doctor of Medicine';
            break;
        case 'GRADSCH':
            $suggestedCourse = 'Master of Arts';
            $suggestedYearLevel = 'Graduate';
            break;
        default:
            $suggestedCourse = 'BSIT'; // Default fallback
    }
    
    echo "Suggested Course: {$suggestedCourse}\n";
    echo "Suggested Year Level: {$suggestedYearLevel}\n";
    
    // Update the student
    $student->update([
        'course' => $suggestedCourse,
        'year_level' => $student->year_level ?: $suggestedYearLevel
    ]);
    
    echo "✓ Updated successfully!\n";
    echo "---\n\n";
}

echo "\n=== Fix Complete! ===\n";
echo "Total students updated: " . $studentsWithoutCourse->count() . "\n";
echo "\nStudents can now see their course in the application form.\n";
echo "Admins can update individual courses via /admin/AddAccount if needed.\n";
