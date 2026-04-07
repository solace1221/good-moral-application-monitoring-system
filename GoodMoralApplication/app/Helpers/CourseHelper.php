<?php

namespace App\Helpers;

use App\Models\Course;

class CourseHelper
{
    /**
     * Get all courses as a flat array (code => full name)
     */
    public static function getAllCourses(): array
    {
        return Course::getAllCourses();
    }

    /**
     * Get courses by department (code only)
     */
    public static function getCoursesByDepartment(): array
    {
        $coursesByDept = [];
        $courses = Course::getByDepartment();

        foreach ($courses as $deptCode => $deptCourses) {
            $coursesByDept[$deptCode] = $deptCourses->keys()->toArray();
        }

        return $coursesByDept;
    }

    /**
     * Get courses by department with full names
     */
    public static function getCoursesByDepartmentWithNames(): array
    {
        return Course::getByDepartment()->toArray();
    }

    /**
     * Get course full name by code
     */
    public static function getCourseName(string $courseCode): string
    {
        $course = Course::where('course_code', $courseCode)->first();
        return $course ? $course->course_name : $courseCode;
    }

    /**
     * Get department name by code
     */
    public static function getDepartmentName(string $deptCode): string
    {
        $department = \App\Models\Department::where('department_code', $deptCode)->first();
        return $department ? $department->department_name : $deptCode;
    }

    /**
     * Get courses for a specific department
     */
    public static function getCoursesForDepartment(string $deptCode): array
    {
        return Course::byDepartmentCode($deptCode)
            ->ordered()
            ->pluck('course_name', 'course_code')
            ->toArray();
    }

    /**
     * Validate if a course exists
     */
    public static function courseExists(string $courseCode): bool
    {
        return Course::where('course_code', $courseCode)->exists();
    }

    /**
     * Get department for a specific course
     */
    public static function getDepartmentForCourse(string $courseCode): ?string
    {
        $course = Course::with('department')->where('course_code', $courseCode)->first();
        return $course && $course->department ? $course->department->department_code : null;
    }

    /**
     * Get all departments
     */
    public static function getAllDepartments(): array
    {
        return Course::getDepartments();
    }

    /**
     * Import courses from CSV file
     */
    public static function importFromCsv(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['success' => false, 'message' => 'File not found'];
        }

        try {
            $data = array_map('str_getcsv', file($filePath));
            $header = array_shift($data);

            // Expected CSV format: course_code, course_name, department, department_name, description, sort_order
            $expectedHeaders = ['course_code', 'course_name', 'department', 'department_name'];

            if (count($header) < 4) {
                return ['success' => false, 'message' => 'CSV must have at least 4 columns: course_code, course_name, department, department_name'];
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            // Clear existing courses (optional - you might want to keep this commented)
            // Course::truncate();

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2;

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Ensure we have enough columns
                while (count($row) < 6) {
                    $row[] = '';
                }

                try {
                    Course::updateOrCreate(
                        ['course_code' => trim($row[0])],
                        [
                            'course_name' => trim($row[1]),
                            'department' => trim($row[2]),
                            'department_name' => trim($row[3]),
                            'description' => trim($row[4]) ?: null,
                            'sort_order' => is_numeric(trim($row[5])) ? (int)trim($row[5]) : 0,
                            'is_active' => true,
                        ]
                    );
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    $errorCount++;
                }
            }

            return [
                'success' => true,
                'message' => "Import completed! {$successCount} courses imported successfully.",
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error processing CSV: ' . $e->getMessage()];
        }
    }
}
