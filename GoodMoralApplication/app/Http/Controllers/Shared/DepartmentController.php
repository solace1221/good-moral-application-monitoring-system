<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\GoodMoralApplication;
use App\Models\RoleAccount;
use App\Models\StudentRegistration;
use App\Models\StudentViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'color' => 'nullable|string|max:20',
            'label' => 'nullable|string|max:255',
        ]);

        $validated['is_undergraduate'] = $request->has('is_undergraduate');

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo' . $validated['department_code'] . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/departments'), $filename);
            $validated['logo'] = $filename;
        }

        Department::create($validated);
        Department::clearCache();

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
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'color' => 'nullable|string|max:20',
            'label' => 'nullable|string|max:255',
        ]);

        $validated['is_undergraduate'] = $request->has('is_undergraduate');

        if ($request->hasFile('logo')) {
            // Delete old logo file if it exists
            if ($department->logo && File::exists(public_path('images/departments/' . $department->logo))) {
                File::delete(public_path('images/departments/' . $department->logo));
            }
            $file = $request->file('logo');
            $filename = 'logo' . $validated['department_code'] . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/departments'), $filename);
            $validated['logo'] = $filename;
        }

        $oldCode = $department->department_code;
        $newCode = $validated['department_code'];
        $codeChanged = $oldCode !== $newCode;

        // Auto-update label when code changes (e.g. "SIE Applications" → "SITE Applications")
        if ($codeChanged && isset($validated['label']) && str_contains($validated['label'], $oldCode)) {
            $validated['label'] = str_replace($oldCode, $newCode, $validated['label']);
        }

        DB::transaction(function () use ($department, $validated, $oldCode, $newCode, $codeChanged) {
            if ($codeChanged) {
                // Cascade department_code change to all tables with denormalized department strings
                $department->courses()->update([
                    'department' => $newCode,
                    'department_name' => $validated['department_name'],
                ]);

                RoleAccount::where('department', $oldCode)->update(['department' => $newCode]);
                StudentViolation::where('department', $oldCode)->update(['department' => $newCode]);
                GoodMoralApplication::where('department', $oldCode)->update(['department' => $newCode]);
                StudentRegistration::where('department', $oldCode)->update(['department' => $newCode]);
            } elseif ($department->department_name !== $validated['department_name']) {
                // If only name changed, still update denormalized department_name on courses
                $department->courses()->update([
                    'department_name' => $validated['department_name'],
                ]);
            }

            $department->update($validated);
        });

        Department::clearCache();

        // If code changed, also clear the old code's individual caches
        // (clearCache only iterates current DB codes, missing the stale old key)
        if ($codeChanged) {
            Cache::forget("dept_name_{$oldCode}");
            Cache::forget("dept_display_{$oldCode}");
        }

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
        Department::clearCache();

        return back()->with('success', "Department {$code} deleted successfully.");
    }
}
