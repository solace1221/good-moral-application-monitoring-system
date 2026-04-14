<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoleAccount;
use App\Models\StudentRegistration;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'student@test.com';
        $studentId = '2024-00001';
        $plainPassword = 'password123';
        $hashedPassword = Hash::make($plainPassword);

        // Look up the first course seeded by CourseSeeder
        $course = Course::first();

        if (!$course) {
            $this->command->error('No courses found. Ensure CourseSeeder runs before StudentSeeder.');
            return;
        }

        $existsInUsers = User::whereRaw('LOWER(email) = ?', [$email])->exists();
        $existsInRoleAccount = RoleAccount::whereRaw('LOWER(email) = ?', [$email])->exists();
        $existsInRegistrations = StudentRegistration::where('student_id', $studentId)->exists();

        if ($existsInUsers && $existsInRoleAccount && $existsInRegistrations) {
            $this->command->info("Skipped {$email}: already exists in all tables");
            return;
        }

        DB::transaction(function () use ($email, $studentId, $plainPassword, $hashedPassword, $course, $existsInUsers, $existsInRoleAccount, $existsInRegistrations) {
            if (!$existsInUsers) {
                User::create([
                    'name' => 'student',
                    'firstname' => 'Juan',
                    'lastname' => 'Dela Cruz',
                    'middlename' => 'Santos',
                    'suffix_name' => null,
                    'email' => $email,
                    'password' => $plainPassword,
                    'status' => 'active',
                    'role' => 'student',
                ]);
            }

            if (!$existsInRoleAccount) {
                RoleAccount::create([
                    'fullname' => 'Dela Cruz, Juan Santos',
                    'mname' => 'Santos',
                    'email' => $email,
                    'password' => $hashedPassword,
                    'account_type' => 'student',
                    'student_id' => $studentId,
                    'department' => $course->department,
                    'course_id' => $course->id,
                    'course' => $course->course_code,
                    'year_level' => '1',
                    'gender' => 'male',
                    'status' => 'active',
                ]);
            }

            if (!$existsInRegistrations) {
                StudentRegistration::create([
                    'fname' => 'Juan',
                    'mname' => 'Santos',
                    'lname' => 'Dela Cruz',
                    'gender' => 'male',
                    'student_id' => $studentId,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'department' => $course->department,
                    'course_id' => $course->id,
                    'course' => $course->course_code,
                    'status' => 'active',
                    'account_type' => 'student',
                    'year_level' => '1',
                ]);
            }
        });

        $this->command->info("Created student account: {$email} (ID: {$studentId}, Course: {$course->course_code})");
    }
}
