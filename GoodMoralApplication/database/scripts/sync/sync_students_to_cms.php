<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Syncing GMAMS Students to CMS ===\n\n";

// Get all students from GMAMS student_registrations table
$gmaStudents = DB::connection('mysql')->table('student_registrations')->get();

echo "Found " . $gmaStudents->count() . " students in GMAMS\n\n";

$synced = 0;
$skipped = 0;
$errors = 0;

foreach ($gmaStudents as $gmaStudent) {
    try {
        // Check if user already exists in CMS
        $cmsUser = DB::connection('mysql2')->table('users')
            ->where('email', $gmaStudent->email)
            ->first();
        
        if (!$cmsUser) {
            // Create user in CMS
            $userId = DB::connection('mysql2')->table('users')->insertGetId([
                'name' => trim(($gmaStudent->fname ?? '') . ' ' . ($gmaStudent->mname ?? '') . ' ' . ($gmaStudent->lname ?? '')),
                'email' => $gmaStudent->email,
                'password' => $gmaStudent->password, // Already hashed
                'firstname' => $gmaStudent->fname,
                'middlename' => $gmaStudent->mname,
                'lastname' => $gmaStudent->lname,
                'role' => 'student',
                'student_number' => $gmaStudent->student_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "✓ Created user: {$gmaStudent->email} (ID: $userId)\n";
        } else {
            $userId = $cmsUser->id;
            
            // Update role if not set
            if (empty($cmsUser->role) || $cmsUser->role !== 'student') {
                DB::connection('mysql2')->table('users')
                    ->where('id', $userId)
                    ->update([
                        'role' => 'student',
                        'student_number' => $gmaStudent->student_id,
                        'updated_at' => now(),
                    ]);
                echo "  Updated role for: {$gmaStudent->email}\n";
            }
        }
        
        // Check if student record exists
        $cmsStudent = DB::connection('mysql2')->table('students')
            ->where('student_number', $gmaStudent->student_id)
            ->first();
        
        if (!$cmsStudent) {
            // Map department from GMAMS to CMS
            $departmentMap = [
                'SITE' => 1,
                'SASTE' => 2,
                'SNAHS' => 3,
                'SBAHM' => 4,
                'GRADSCH' => 5,
                'Graduate School' => 5,
            ];
            
            $departmentId = $departmentMap[$gmaStudent->department] ?? 1;
            
            // Map course  to CMS (get course_id from courses table)
            $course = DB::connection('mysql2')->table('courses')
                ->where('course_code', $gmaStudent->course)
                ->orWhere('course_name', 'like', '%' . $gmaStudent->course . '%')
                ->first();
            
            $courseId = $course->id ?? null;
            
            // Parse year_level (e.g., "BS Information Technology - 4" -> year=4)
            $year = 1;
            if (preg_match('/(\d)/', $gmaStudent->year_level, $matches)) {
                $year = (int)$matches[1];
            }
            
            // Create student record
            DB::connection('mysql2')->table('students')->insert([
                'student_number' => $gmaStudent->student_id,
                'firstname' => $gmaStudent->fname,
                'middlename' => $gmaStudent->mname ?? '',
                'lastname' => $gmaStudent->lname,
                'email' => $gmaStudent->email,
                'department_id' => $departmentId,
                'course_id' => $courseId,
                'year_level' => $year,
                'users_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "  ✓ Created student record: {$gmaStudent->student_id}\n";
            $synced++;
        } else {
            // Update users_id if not set
            if (empty($cmsStudent->users_id)) {
                DB::connection('mysql2')->table('students')
                    ->where('student_number', $gmaStudent->student_id)
                    ->update([
                        'users_id' => $userId,
                        'updated_at' => now(),
                    ]);
                echo "  Updated student users_id: {$gmaStudent->student_id}\n";
                $synced++;
            } else {
                $skipped++;
            }
        }
        
    } catch (\Exception $e) {
        echo "✗ Error syncing {$gmaStudent->email}: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Sync Complete ===\n";
echo "Synced: $synced\n";
echo "Skipped: $skipped\n";
echo "Errors: $errors\n";
