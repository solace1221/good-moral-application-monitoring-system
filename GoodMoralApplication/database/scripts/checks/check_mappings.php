<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Department Mapping:\n";
    echo "==================\n";
    $departments = DB::table('departments')->select('id', 'department_code', 'department_name')->get();
    foreach($departments as $dept) {
        echo sprintf("%2d - %-30s (%s)\n", $dept->id, $dept->department_name, $dept->department_code ?? 'N/A');
    }

    echo "\n\nCurrent Academic Year:\n";
    echo "=====================\n";
    $currentAY = DB::table('academic_years')->where('is_current', 1)->first();
    if ($currentAY) {
        echo "ID: {$currentAY->id}\n";
        echo "Year: {$currentAY->year}\n";
        echo "Semester: {$currentAY->semester}\n";
    } else {
        echo "No current academic year set\n";
    }

    echo "\n\nSample Courses:\n";
    echo "===============\n";
    $courses = DB::table('courses')->select('id', 'course_code', 'course_name', 'department_id')->limit(10)->get();
    foreach($courses as $course) {
        echo sprintf("%2d - %-10s %-40s (Dept: %d)\n", $course->id, $course->course_code, $course->course_name, $course->department_id);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
