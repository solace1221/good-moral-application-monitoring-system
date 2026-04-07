# Semester Dropdown Implementation

## Overview

The application form includes a structured dropdown for "Semester and School Year of Last Attendance in SPUP." The field uses a predefined list of options to ensure consistent data entry.

---

## Available Options

- First Semester of 2023-2024
- Second Semester of 2023-2024
- Summer Term of 2023-2024
- First Semester of 2024-2025
- Second Semester of 2024-2025
- Summer Term of 2024-2025

---

## Frontend — Blade Template

```blade
<div class="responsive-form-group">
    <label for="last_semester_sy">
        Semester and School Year of Last Attendance in SPUP *
    </label>
    <select id="last_semester_sy" name="last_semester_sy" required>
        <option value="" disabled selected>Select semester and school year</option>
        <option value="First Semester of 2023-2024"
            {{ old('last_semester_sy') == 'First Semester of 2023-2024' ? 'selected' : '' }}>
            First Semester of 2023-2024
        </option>
        <option value="Second Semester of 2023-2024"
            {{ old('last_semester_sy') == 'Second Semester of 2023-2024' ? 'selected' : '' }}>
            Second Semester of 2023-2024
        </option>
        <option value="Summer Term of 2023-2024"
            {{ old('last_semester_sy') == 'Summer Term of 2023-2024' ? 'selected' : '' }}>
            Summer Term of 2023-2024
        </option>
        <option value="First Semester of 2024-2025"
            {{ old('last_semester_sy') == 'First Semester of 2024-2025' ? 'selected' : '' }}>
            First Semester of 2024-2025
        </option>
        <option value="Second Semester of 2024-2025"
            {{ old('last_semester_sy') == 'Second Semester of 2024-2025' ? 'selected' : '' }}>
            Second Semester of 2024-2025
        </option>
        <option value="Summer Term of 2024-2025"
            {{ old('last_semester_sy') == 'Summer Term of 2024-2025' ? 'selected' : '' }}>
            Summer Term of 2024-2025
        </option>
    </select>
    @error('last_semester_sy')
        <span class="text-red-600 text-sm">{{ $message }}</span>
    @enderror
</div>
```

---

## Backend Validation

```php
$validSemesters = [
    'First Semester of 2023-2024',
    'Second Semester of 2023-2024',
    'Summer Term of 2023-2024',
    'First Semester of 2024-2025',
    'Second Semester of 2024-2025',
    'Summer Term of 2024-2025',
];

$request->validate([
    'last_semester_sy' => ['required', 'string', 'in:' . implode(',', $validSemesters)],
]);
```

---

## Files Modified

- Application form Blade view — dropdown added
- `app/Http/Controllers/ApplicationController.php` — validation rule added

---

## Notes

When a new academic year begins, add the new semester options to both the Blade template and the `$validSemesters` array in the controller. The two lists must stay in sync to prevent valid entries being rejected by server-side validation.
