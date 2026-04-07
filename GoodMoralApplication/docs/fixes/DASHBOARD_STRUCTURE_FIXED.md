# Fix: Dashboard Column Structure and Official Data

## Problem

The trends analysis dashboard (Admin and SecOSA) had two issues:
1. Column headers did not show the academic year labels explicitly.
2. Previous AY (2023–2024) data was missing — columns showed zeros.

---

## Solution

### 1. Updated Column Headers

| Before | After |
|--------|-------|
| `Number of Student Violators (Previous AY)` | `Number of Student Violators (Previous AY – AY 2023-2024)` |
| `Number of Student Violators (Current AY)` | `Number of Student Violators (Current AY – AY 2024-2025)` |

### 2. Hardcoded Previous AY Official Data

The official 2023–2024 violation data from SPUP records was hardcoded in the controller methods:

**Major Offenses — AY 2023–2024:**

| Department | Count |
|------------|-------|
| SITE | 9 |
| SBAHM | 15 |
| SNAHS | 79 |
| SASTE | 4 |
| **Total** | **107** |

**Minor Offenses — AY 2023–2024:**

| Department | Count |
|------------|-------|
| SITE | 118 |
| SBAHM | 88 |
| SNAHS | 524 |
| SASTE | 97 |
| **Total** | **827** |

### 3. Real-Time Current AY

The Current AY column queries `student_violations` in real time, filtered by:
- Offense type (major/minor)
- Current academic year date range
- Distinct `student_id` (no duplicate counting)

### 4. Automatic Variance Calculation

```
Variance (%) = ((Previous AY - Current AY) / Previous AY) × 100
```

Calculated to two decimal places. Trend indicators:
- **Increasing** (red) — Current AY > Previous AY
- **Decreasing** (green) — Current AY < Previous AY
- **Stable** (gray) — Current AY = Previous AY

---

## Files Modified

- `app/Http/Controllers/AdminController.php` — `getMinorOffensesTrendsData()`, `getTrendsAnalysisData()`
- `app/Http/Controllers/SecOSAController.php` — same methods, identical data
- Corresponding dashboard Blade views — updated column headers
