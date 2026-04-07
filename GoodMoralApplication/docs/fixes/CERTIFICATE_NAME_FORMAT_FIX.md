# Fix: Certificate Name Format

## Problem

Names on generated PDF certificates were not formatted consistently. Some certificates displayed names in all-uppercase, others in mixed case, and name suffixes (Jr., III, etc.) were sometimes dropped or duplicated.

---

## Root Cause

Name formatting logic was duplicated across multiple Blade templates, each with slightly different implementations. When a template was updated, others were not kept in sync, leading to inconsistent output.

---

## Solution

Consolidated all name formatting into a single global helper function:

**File**: `app/helpers.php`

```php
if (!function_exists('formatNameForCertificate')) {
    function formatNameForCertificate(string $firstName, string $middleName, string $lastName, string $extension = ''): string
    {
        $name = strtoupper(trim($lastName)) . ', '
              . strtoupper(trim($firstName));

        if (!empty(trim($middleName))) {
            // Use middle initial only
            $name .= ' ' . strtoupper(substr(trim($middleName), 0, 1)) . '.';
        }

        if (!empty(trim($extension))) {
            $name .= ' ' . strtoupper(trim($extension));
        }

        return $name;
    }
}
```

**Format output example:**
```
DELA CRUZ, JUAN A. JR.
```

The helper is auto-loaded via `composer.json` (`autoload.files`) so it is available everywhere without manual imports.

---

## Files Modified

- `app/helpers.php` — added `formatNameForCertificate()`
- All certificate Blade templates — replaced inline name logic with `{{ formatNameForCertificate(...) }}`

---

## Notes

Previously the function was defined directly inside some Blade templates using `@php` blocks, causing `PHP Fatal error: Cannot redeclare formatNameForCertificate()` when multiple templates were included on the same request. Moving it to `helpers.php` with an `if (!function_exists(...))` guard resolves this.

See also [FUNCTION_REDECLARATION_FIX.md](FUNCTION_REDECLARATION_FIX.md).
