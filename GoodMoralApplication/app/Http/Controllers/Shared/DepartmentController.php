<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display the department management page
     */
    public function index()
    {
        $departments = Department::withCount('courses')
            ->orderBy('department_code')
            ->get();

        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Store a new department
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_code' => 'required|string|max:20|unique:departments,department_code',
            'department_name' => 'required|string|max:255',
        ]);

        Department::create($validated);

        return back()->with('success', "Department {$validated['department_code']} created successfully.");
    }

    /**
     * Update an existing department
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'department_code' => 'required|string|max:20|unique:departments,department_code,' . $department->id,
            'department_name' => 'required|string|max:255',
        ]);

        // If department_code changed, update courses that reference the old code
        if ($department->department_code !== $validated['department_code']) {
            $department->courses()->update([
                'department' => $validated['department_code'],
                'department_name' => $validated['department_name'],
            ]);
        } elseif ($department->department_name !== $validated['department_name']) {
            // If only name changed, still update denormalized department_name on courses
            $department->courses()->update([
                'department_name' => $validated['department_name'],
            ]);
        }

        $department->update($validated);

        return back()->with('success', "Department {$validated['department_code']} updated successfully.");
    }

    /**
     * Delete a department
     */
    public function destroy(Department $department)
    {
        if ($department->courses()->exists()) {
            return back()->with('error', "Cannot delete department {$department->department_code} — it still has courses assigned.");
        }

        $code = $department->department_code;
        $department->delete();

        return back()->with('success', "Department {$code} deleted successfully.");
    }
}
