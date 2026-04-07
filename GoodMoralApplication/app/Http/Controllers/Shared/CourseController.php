<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Helpers\CourseHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display the course management page
     */
    public function index()
    {
        $courses = Course::with([])
            ->orderBy('department')
            ->orderBy('sort_order')
            ->orderBy('course_name')
            ->get()
            ->groupBy('department');

        $departments = Course::getDepartments();
        $totalCourses = Course::count();
        $activeCourses = Course::active()->count();

        return view('admin.courses.index', compact('courses', 'departments', 'totalCourses', 'activeCourses'));
    }

    /**
     * Show the course upload form
     */
    public function uploadForm()
    {
        return view('admin.courses.upload');
    }

    /**
     * Handle CSV file upload and import courses
     */
    public function uploadCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            'clear_existing' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $file = $request->file('csv_file');
            $filename = 'courses_' . time() . '.csv';
            $path = $file->storeAs('courses', $filename);
            $fullPath = storage_path('app/' . $path);

            // Clear existing courses if requested
            if ($request->boolean('clear_existing')) {
                Course::truncate();
            }

            // Import courses from CSV
            $result = CourseHelper::importFromCsv($fullPath);

            if ($result['success']) {
                return back()->with('success', $result['message'])
                    ->with('import_details', [
                        'success_count' => $result['success_count'],
                        'error_count' => $result['error_count'],
                        'errors' => $result['errors'] ?? []
                    ]);
            } else {
                return back()->with('error', $result['message']);
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error uploading file: ' . $e->getMessage());
        }
    }

    /**
     * Download sample CSV template
     */
    public function downloadTemplate()
    {
        $samplePath = storage_path('app/sample_courses.csv');

        if (file_exists($samplePath)) {
            return response()->download($samplePath, 'course_template.csv');
        }

        // Create a simple template if sample doesn't exist
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="course_template.csv"',
        ];

        $template = "course_code,course_name,department,department_name,description,sort_order\n";
        $template .= "BSIT,Bachelor of Science in Information Technology,SITE,School of Information Technology and Engineering,IT program,1\n";
        $template .= "BSN,Bachelor of Science in Nursing,SNAHS,School of Nursing and Allied Health Sciences,Nursing program,1\n";

        return response($template, 200, $headers);
    }

    /**
     * Toggle course active status
     */
    public function toggleStatus(Course $course)
    {
        $course->is_active = !$course->is_active;
        $course->save();

        $status = $course->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Course {$course->course_code} has been {$status}.");
    }

    /**
     * Delete a course
     */
    public function destroy(Course $course)
    {
        $courseCode = $course->course_code;
        $course->delete();

        return back()->with('success', "Course {$courseCode} has been deleted.");
    }

    /**
     * API endpoint to get courses for AJAX requests
     */
    public function apiGetCourses(Request $request)
    {
        $department = $request->get('department');
        $activeOnly = $request->boolean('active_only', true);

        if ($department) {
            $courses = CourseHelper::getCoursesForDepartment($department);
        } else {
            $courses = CourseHelper::getAllCourses();
        }

        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    }
}
