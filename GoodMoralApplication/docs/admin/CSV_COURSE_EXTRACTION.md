# CSV Course Extraction

## Overview

Courses are imported into GMAMS from CSV files uploaded by the admin. The CSV parser understands common SPUP course-year formats and extracts the course code and year level automatically.

---

## Expected CSV Format

```csv
course_code,course_name,department,department_name
BSIT,Bachelor of Science in Information Technology,SITE,School of Information Technology Engineering
BSN,Bachelor of Science in Nursing,SNAHS,School of Nursing and Health Sciences
```

---

## Intelligent Course-Year Parsing

When student registration data from legacy systems or imports includes combined course-year strings (e.g., `"BSIT 1st Year"`), the parser splits them:

```php
public static function parseCourseYear(string $courseYear): array
{
    // Match patterns like "BSIT 1st Year", "BSN 2nd Year", "BSBA 3rd Year"
    if (preg_match('/^([A-Z]+)\s+(\d+(?:st|nd|rd|th)\s+Year)$/i', trim($courseYear), $matches)) {
        return [
            'course'     => strtoupper($matches[1]),
            'year_level' => ucfirst(strtolower($matches[2])),
        ];
    }

    // Fallback: return the whole string as course, empty year
    return ['course' => $courseYear, 'year_level' => ''];
}
```

**Examples:**

| Input | course | year_level |
|-------|--------|------------|
| `BSIT 1st Year` | `BSIT` | `1st Year` |
| `BSN 2nd Year` | `BSN` | `2nd Year` |
| `BSBA` | `BSBA` | `` |

---

## Import Process

1. Admin uploads a CSV file.
2. The import controller reads the file line by line using `fgetcsv()`.
3. For each row, it validates required fields (`course_code`, `course_name`, `department`).
4. The course is inserted or updated using `Course::updateOrCreate()`:

```php
Course::updateOrCreate(
    ['course_code' => $row['course_code']],
    [
        'course_name'     => $row['course_name'],
        'department'      => $row['department'],
        'department_name' => $row['department_name'] ?? '',
        'is_active'       => true,
    ]
);
```

---

## Error Handling

- Rows with missing `course_code` or `course_name` are skipped with a warning.
- Duplicate `course_code` entries trigger an update (not a duplicate insert).
- Import errors are collected and displayed in the admin UI after processing.

---

## Related Files

| File | Description |
|------|-------------|
| `app/Http/Controllers/AdminController.php` | `importCourses()` method |
| `app/Helpers/CourseHelper.php` | `parseCourseYear()` utility |
| `app/Models/Course.php` | Course model |
