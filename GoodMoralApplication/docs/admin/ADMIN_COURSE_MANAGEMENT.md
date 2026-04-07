# Admin: Course and Year Level Management

## Overview

Administrators can manage courses and year levels for each SPUP department directly from the Admin dashboard. Courses are imported from CSV files and used system-wide.

---

## Course Management

### Importing Courses from CSV

1. Log in as Admin.
2. Navigate to **Admin → Course Management**.
3. Click **Import Courses**.
4. Upload a CSV file in the following format:

```csv
course_code,course_name,department,department_name
BSIT,Bachelor of Science in Information Technology,SITE,School of Information Technology Engineering
BSN,Bachelor of Science in Nursing,SNAHS,School of Nursing and Health Sciences
BSBA,Bachelor of Science in Business Administration,SBAHM,School of Business Administration and Hotel Management
```

5. Click **Process**. The system parses the CSV and inserts or updates courses in the `courses` table.

---

## Year Level Management

Year levels are predefined and linked to courses. The standard levels are:

- 1st Year
- 2nd Year
- 3rd Year
- 4th Year
- 5th Year (for five-year programs)
- Graduate (for graduate school courses)

Administrators can toggle year levels per course in the course management interface.

---

## Department Codes

| Code | Full Name |
|------|-----------|
| SITE | School of Information Technology Engineering |
| SBAHM | School of Business Administration and Hotel Management |
| SNAHS | School of Nursing and Health Sciences |
| SASTE | School of Arts, Sciences, and Teacher Education |

---

## Related Files

| File | Description |
|------|-------------|
| `app/Http/Controllers/AdminController.php` | Course import and management logic |
| `app/Models/Course.php` | Course model |
| `config/courses.php` | Static course configuration fallback |
| `app/Helpers/CourseHelper.php` | Course lookup utilities |

For the CSV parsing logic, see [CSV_COURSE_EXTRACTION.md](CSV_COURSE_EXTRACTION.md).  
For the course system architecture, see [COURSE_SYSTEM_IMPLEMENTATION.md](COURSE_SYSTEM_IMPLEMENTATION.md).
