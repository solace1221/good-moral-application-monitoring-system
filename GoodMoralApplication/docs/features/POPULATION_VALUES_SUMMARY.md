# Population Values Summary

## Overview

Department population values are used on the Good Moral Certificate to display total student enrollment per college. These values are fetched from the database when available, with hardcoded fallbacks for reliability.

---

## Official Population Figures

| College Code | Full Name | Student Population |
|---|---|---|
| SITE | School of Information Technology and Engineering | 640 |
| SBAHM | School of Business Administration, Hospitality and Management | 727 |
| SNAHS | School of Nursing and Allied Health Sciences | 2,831 |
| SASTE | School of Arts, Sciences, and Teacher Education | 409 |
| **Total** | All Colleges | **4,607** |

---

## Query + Fallback Pattern

```php
// Example: get population for a college
$population = DB::table('department_populations')
    ->where('college_code', $college)
    ->value('total_count');

if (!$population) {
    $fallbacks = [
        'SITE'  => 640,
        'SBAHM' => 727,
        'SNAHS' => 2831,
        'SASTE' => 409,
    ];
    $population = $fallbacks[$college] ?? 0;
}
```

---

## Recommendations

- Store all population values in a `department_populations` database table.
- Update values each semester/academic year via the Admin panel.
- The fallback hardcoded values should only be used as a safety net, not as primary data.
- Add an Admin UI to update these counts without code changes.
