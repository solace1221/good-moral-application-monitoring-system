# Course System Implementation

## Overview

GMAMS uses a centralized course management system. Course data is stored in the database (imported from CSV) and referenced throughout the application via a `CourseHelper` class and a `config/courses.php` fallback.

---

## Architecture

```
CSV Upload (Admin UI)
      │
      ▼
AdminController::importCourses()
      │
      ▼
  courses table (MySQL)
      │
    ┌─┴──────────────┐
    │                │
CourseHelper      config/courses.php
(DB lookup)       (static fallback)
    │
    ▼
Application forms, profile displays, certificate generation
```

---

## Database Structure

```sql
CREATE TABLE courses (
    id          BIGINT PRIMARY KEY AUTO_INCREMENT,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    department  VARCHAR(10) NOT NULL,
    department_name VARCHAR(255) NOT NULL,
    is_active   BOOLEAN DEFAULT TRUE,
    description TEXT,
    sort_order  INT DEFAULT 0,
    created_at  TIMESTAMP,
    updated_at  TIMESTAMP
);
```

---

## CourseHelper

**File**: `app/Helpers/CourseHelper.php`

```php
class CourseHelper
{
    public static function getCourseName(string $courseCode): ?string
    {
        // Check database first
        $course = Course::where('course_code', $courseCode)->first();
        if ($course) {
            return $course->course_name;
        }

        // Fallback to config
        return config("courses.{$courseCode}.name");
    }

    public static function getCoursesByDepartment(string $department): Collection
    {
        return Course::where('department', $department)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
```

---

## Static Fallback (`config/courses.php`)

Used when the database is unavailable or a course is not yet imported:

```php
return [
    'BSIT' => ['name' => 'Bachelor of Science in Information Technology', 'department' => 'SITE'],
    'BSN'  => ['name' => 'Bachelor of Science in Nursing', 'department' => 'SNAHS'],
    'BSBA' => ['name' => 'Bachelor of Science in Business Administration', 'department' => 'SBAHM'],
    // ...
];
```

---

## Usage in Views

```blade
{{-- Display course name from student profile --}}
{{ CourseHelper::getCourseName($student->course) ?? $student->course }}
```

---

## Related Files

| File | Description |
|------|-------------|
| `app/Helpers/CourseHelper.php` | Course lookup helper |
| `config/courses.php` | Static fallback course data |
| `app/Models/Course.php` | Eloquent model for courses table |
| `app/Http/Controllers/AdminController.php` | CSV import logic |
