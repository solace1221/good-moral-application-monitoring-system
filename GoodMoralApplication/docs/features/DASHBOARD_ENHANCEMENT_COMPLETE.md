# Dashboard Enhancement — Trends Analysis

## Overview

The Admin and SecOSA dashboards feature a Trends Analysis section that compares student violation counts between the previous academic year (AY 2023–2024) and the current academic year (AY 2024–2025) per department.

---

## Data Sources

| Column | Source |
|--------|--------|
| Total Population | Hardcoded official SPUP enrollment figures |
| Previous AY violations | Hardcoded official records (AY 2023–2024) |
| Current AY violations | Real-time query from `student_violations` table |
| Variance (%) | Auto-calculated from Previous and Current AY |

---

## Official Data (Hardcoded)

### Minor Offenses — AY 2023–2024

| Department | Population | Violators |
|------------|-----------|-----------|
| SITE | 640 | 118 |
| SBAHM | 727 | 88 |
| SNAHS | 2,831 | 524 |
| SASTE | 409 | 97 |
| **Total** | **4,607** | **827** |

### Major Offenses — AY 2023–2024

| Department | Population | Violators |
|------------|-----------|-----------|
| SITE | 640 | 9 |
| SBAHM | 727 | 15 |
| SNAHS | 2,831 | 79 |
| SASTE | 409 | 4 |
| **Total** | **4,607** | **107** |

---

## Variance Formula

```
Variance (%) = ((Previous AY - Current AY) / Previous AY) × 100
```

- **Positive percentage** → fewer violations in current AY (improvement)
- **Negative percentage** → more violations in current AY (concern)

---

## Trend Indicators

| Indicator | Condition | Color |
|-----------|-----------|-------|
| Increasing | Current AY > Previous AY | Red |
| Decreasing | Current AY < Previous AY | Green |
| Stable | Current AY = Previous AY | Gray |

---

## Backend Implementation

Both `AdminController` and `SecOSAController` implement:
- `getTrendsAnalysisData()` — major offenses trends
- `getMinorOffensesTrendsData()` — minor offenses trends

Both methods return the same data structure for consistency between the two dashboards.

---

## Real-Time Query

```php
$currentViolators = StudentViolation::where('offense_type', $type)
    ->whereBetween('created_at', [$academicYearStart, $academicYearEnd])
    ->distinct('student_id')
    ->count('student_id');
```
