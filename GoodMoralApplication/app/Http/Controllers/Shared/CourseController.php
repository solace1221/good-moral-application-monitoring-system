<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Department;
use App\Helpers\CourseHelper;

class CourseController extends Controller
{
    /**
     * Display the course management page
     */
    public function index(Request $request)
    {
        $query = Course::orderBy('department')
            ->orderBy('sort_order')
            ->orderBy('course_name');

        if ($request->filled('search_name')) {
            $query->where('course_name', 'LIKE', '%' . $request->search_name . '%');
        }
        if ($request->filled('search_department')) {
            $query->where('department', $request->search_department);
        }

        $courses = $query->paginate(10)->appends($request->query());
        $departments = Course::getDepartments();
        $totalCourses = Course::count();

        return view('admin.courses.index', compact('courses', 'departments', 'totalCourses'));
    }

    /**
     * Store a new course
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|unique:courses,course_code',
            'course_name' => 'required|string|max:300',
            'department' => 'required|string|max:10|exists:departments,department_code',
        ]);

        $dept = Department::where('department_code', $validated['department'])->first();
        $validated['department_name'] = $dept->department_name;
        $validated['sort_order'] = Course::where('department', $validated['department'])->max('sort_order') + 1;

        Course::create($validated);

        return back()->with('success', "Course {$validated['course_code']} created successfully.");
    }

    /**
     * Update an existing course
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|unique:courses,course_code,' . $course->id,
            'course_name' => 'required|string|max:300',
            'department' => 'required|string|max:10|exists:departments,department_code',
        ]);

        $dept = Department::where('department_code', $validated['department'])->first();
        $validated['department_name'] = $dept->department_name;

        $course->update($validated);

        return back()->with('success', "Course {$validated['course_code']} updated successfully.");
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
