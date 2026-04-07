# Fix: Department Population Values

## Problem

The trends analysis dashboard showed zero or incorrect values for "Total Population" per department because the system was querying student counts from the database, where not all SPUP students were enrolled or registered in GMAMS.

---

## Root Cause

The original implementation calculated population by counting records in `student_registrations`, which only includes students who had registered in GMAMS. The actual SPUP department enrollment figures are larger and must be sourced from official institutional data.

---

## Solution

Hardcoded the official SPUP department enrollment figures in both `AdminController` and `SecOSAController`:

```php
private function getDepartmentPopulations(): array
{
    return [
        'SITE'  => 640,
        'SBAHM' => 727,
        'SNAHS' => 2831,
        'SASTE' => 409,
    ];
}
```

Total enrollment: 4,607 students.

The dashboard uses a fallback pattern: database query first, hardcoded value if the query returns zero or null:

```php
$population = $dbPopulation > 0 ? $dbPopulation : $this->getDepartmentPopulations()[$dept];
```

---

## Files Modified

- `app/Http/Controllers/AdminController.php` — hardcoded population values added
- `app/Http/Controllers/SecOSAController.php` — same values added

---

## Notes

When official enrollment figures change (new academic year), update the values in both controllers. Consider moving these to a configuration file (`config/population.php`) or a database table for easier maintenance in future.
