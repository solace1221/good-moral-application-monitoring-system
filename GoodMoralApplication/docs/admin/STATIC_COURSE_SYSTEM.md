# Static Course System

## Overview

Course information on the application form is **read-only**. It is automatically populated from the student's profile and cannot be changed by the student during application submission. This ensures data consistency across all applications.

---

## How It Works

1. When a student registers, their course is stored in `role_account.course` (e.g., `BSIT`).
2. When the student opens the application form, `ApplicationController::dashboard()` retrieves the course from their profile.
3. The course is displayed as a static field — no `<select>` or editable `<input>`, only a read-only display with a hidden input for form submission.

---

## Controller

```php
public function dashboard()
{
    $student = RoleAccount::where('id', auth()->user()->id)->first();

    $studentCourse     = $student->course;
    $studentCourseName = $studentCourse
        ? CourseHelper::getCourseName($studentCourse)
        : null;

    return view('dashboard', compact('studentCourse', 'studentCourseName'));
}
```

---

## Blade Template

```blade
<div class="responsive-form-group">
    <label>Course Completed</label>

    @if($studentCourse && $studentCourseName)
        <div class="static-field">
            <span class="font-semibold">{{ $studentCourse }}</span>
            — {{ $studentCourseName }}
            <x-icon name="lock" size="16" class="text-gray-400 ml-2" />
        </div>
        <input type="hidden" name="course_completed" value="{{ $studentCourse }}">
    @else
        <p class="text-sm text-gray-500">
            No course on file. Please contact the administrator.
        </p>
    @endif
</div>
```

---

## Why Static?

- Prevents students from misrepresenting their enrolled course on certificate applications.
- Ensures the course on the certificate matches official enrollment records.
- Eliminates data entry errors from free-text or unrestricted dropdown input.

---

## Updating a Student's Course

Only an Administrator can update a student's course, through the User Management section:

1. Admin → Users → find student → Edit
2. Update the **Course** field
3. Save — updates `role_account.course` and `student_registrations.course`

---

## Related Files

| File | Description |
|------|-------------|
| `app/Http/Controllers/ApplicationController.php` | Retrieves course from profile |
| `app/Helpers/CourseHelper.php` | Looks up full course name |
| `app/Models/RoleAccount.php` | Student profile model |
