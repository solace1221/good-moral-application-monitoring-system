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
        $course = Course::where('course_code', $courseCode)->first();
        return $course ? $course->department : null;
    }

    /**
     * Get all departments
     */
    public static function getAllDepartments(): array
    {
        return Course::getDepartments();
    }

}
