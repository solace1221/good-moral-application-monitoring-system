# Fix: Dean Routes Access Control

## Problem

Dean routes (`/dean/*`) were accessible by non-dean users. The three dean role types (`dean`, `deansom`, `deangradsch`) were not all covered by the middleware.

---

## Root Cause

The dean route group had no role middleware, or had only `role:dean` which excluded `deansom` and `deangradsch`.

---

## Solution

Updated the dean route group middleware to include all three dean roles:

```php
Route::middleware(['auth', 'role:dean,deansom,deangradsch'])
    ->prefix('dean')
    ->name('dean.')
    ->group(function () {
        Route::get('/dashboard', [DeanController::class, 'dashboard'])->name('dashboard');
        Route::get('/violations/major', [DeanController::class, 'majorViolations'])->name('violations.major');
        Route::get('/violations/minor', [DeanController::class, 'minorViolations'])->name('violations.minor');
        // ...
    });
```

---

## Files Modified

- `routes/web.php` — dean route group middleware updated

---

## Notes

The three dean roles differ in scope:
- `dean` — standard school dean (SITE, SBAHM, SNAHS, SASTE)
- `deansom` — Dean of Student Organization Management
- `deangradsch` — Dean of Graduate School

All three share the same routes and views, filtered by department where applicable.

---

## Testing

1. Log in as each of the three dean types → confirm `/dean/dashboard` loads.
2. Log in as admin or student → confirm `/dean/dashboard` redirects with access denied.
