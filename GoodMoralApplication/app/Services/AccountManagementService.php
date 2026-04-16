<?php

namespace App\Services;

use App\Models\RoleAccount;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountManagementService
{
    /**
     * Parse course_year field from CSV (e.g., "BSIT 1st Year").
     */
    public function parseCourseYear(string $courseYearField): array
    {
        $courseYearField = trim($courseYearField);

        if (empty($courseYearField)) {
            return ['course' => null, 'year_level' => null];
        }

        // Common year level patterns
        $yearLevelPatterns = [
            '1st year', '2nd year', '3rd year', '4th year', '5th year',
            'first year', 'second year', 'third year', 'fourth year', 'fifth year',
        ];

        foreach ($yearLevelPatterns as $pattern) {
            $pos = stripos($courseYearField, $pattern);
            if ($pos !== false) {
                $course = trim(substr($courseYearField, 0, $pos));
                $yearLevel = trim(substr($courseYearField, $pos));
                return ['course' => $course, 'year_level' => $yearLevel];
            }
        }

        // No year level found — treat entire field as course
        return ['course' => $courseYearField, 'year_level' => null];
    }

    /**
     * Import users from parsed CSV data.
     *
     * @param array  $rows    Array of CSV row arrays (header removed)
     * @param string $defaultPassword
     * @return array ['successCount' => int, 'errorCount' => int, 'errors' => string[]]
     */
    public function importUsersFromCsv(array $rows, string $defaultPassword = 'student123'): array
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            if (empty(array_filter($row))) {
                continue;
            }

            while (count($row) < 8) {
                $row[] = '';
            }

            $parsedCourse = $this->parseCourseYear(trim($row[6]));

            $studentData = [
                'student_id' => trim($row[0]),
                'fname' => trim($row[1]),
                'mname' => trim($row[2]) ?: null,
                'lname' => trim($row[3]),
                'extension' => trim($row[4]) ?: null,
                'department' => trim($row[5]),
                'course' => $parsedCourse['course'],
                'year_level' => $parsedCourse['year_level'],
                'email' => strtolower(trim($row[7])),
            ];

            // Validate required fields
            if (empty($studentData['student_id']) || empty($studentData['fname']) ||
                empty($studentData['lname']) || empty($studentData['email']) ||
                empty($studentData['department'])) {
                $errors[] = "Row {$rowNumber}: Missing required fields (student_id, first_name, last_name, department, email)";
                $errorCount++;
                continue;
            }

            if (!filter_var($studentData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row {$rowNumber}: Invalid email format";
                $errorCount++;
                continue;
            }

            if (RoleAccount::where('student_id', $studentData['student_id'])->exists()) {
                $errors[] = "Row {$rowNumber}: Student ID {$studentData['student_id']} already exists";
                $errorCount++;
                continue;
            }

            if (RoleAccount::whereRaw('LOWER(email) = ?', [$studentData['email']])->exists()) {
                $errors[] = "Row {$rowNumber}: Email {$studentData['email']} already exists in role_account";
                $errorCount++;
                continue;
            }

            if (User::whereRaw('LOWER(email) = ?', [$studentData['email']])->exists()) {
                $errors[] = "Row {$rowNumber}: Email {$studentData['email']} already exists in users";
                $errorCount++;
                continue;
            }

            try {
                $fullname = $studentData['lname'] . ', ' . $studentData['fname'];
                if ($studentData['mname']) {
                    $fullname .= ' ' . $studentData['mname'];
                }
                if ($studentData['extension']) {
                    $fullname .= ' ' . $studentData['extension'];
                }

                // Hash password once for role_account and student_registrations
                $hashedPassword = Hash::make($defaultPassword);

                DB::transaction(function () use ($studentData, $fullname, $defaultPassword, $hashedPassword) {
                    // 1. Create login record in users table
                    //    User model has 'password' => 'hashed' cast, so pass plain-text
                    User::create([
                        'name' => strtolower($studentData['fname'] . '.' . $studentData['lname']),
                        'firstname' => $studentData['fname'],
                        'lastname' => $studentData['lname'],
                        'middlename' => $studentData['mname'],
                        'suffix_name' => $studentData['extension'],
                        'email' => $studentData['email'],
                        'password' => $defaultPassword,
                        'role' => 'student',
                        'status' => 'active',
                    ]);

                    // 2. Create profile in role_account
                    RoleAccount::create([
                        'fullname' => $fullname,
                        'mname' => $studentData['mname'],
                        'extension' => $studentData['extension'],
                        'department' => $studentData['department'],
                        'course' => $studentData['course'],
                        'year_level' => $studentData['year_level'],
                        'email' => $studentData['email'],
                        'password' => $hashedPassword,
                        'student_id' => $studentData['student_id'],
                        'status' => 'active',
                        'account_type' => 'student',
                        'created_via' => 'import',
                        'organization' => null,
                        'position' => null,
                    ]);

                    // 3. Create enrollment record in student_registrations
                    StudentRegistration::create([
                        'fname' => $studentData['fname'],
                        'mname' => $studentData['mname'],
                        'lname' => $studentData['lname'],
                        'extension' => $studentData['extension'],
                        'email' => $studentData['email'],
                        'department' => $studentData['department'],
                        'course' => $studentData['course'],
                        'password' => $hashedPassword,
                        'student_id' => $studentData['student_id'],
                        'status' => 'active',
                        'account_type' => 'student',
                        'year_level' => $studentData['year_level'],
                        'organization' => null,
                        'position' => null,
                    ]);
                });

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: Error creating account - " . $e->getMessage();
                $errorCount++;
            }
        }

        return compact('successCount', 'errorCount', 'errors');
    }
}
