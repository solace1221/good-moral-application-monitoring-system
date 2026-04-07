# Fix: PHP Function Redeclaration Error

## Problem

A `PHP Fatal error: Cannot redeclare formatNameForCertificate()` occurred when generating certificates. The error appeared only on certain pages where multiple Blade templates were included on the same request.

---

## Root Cause

The `formatNameForCertificate()` function was defined inside `@php` blocks within Blade templates. When two templates that both defined this function were included in the same view (e.g., a certificate layout and a print preview partial), PHP threw a fatal redeclaration error.

---

## Solution

Moved the function to the global helpers file, using `function_exists` guard:

**File**: `app/helpers.php`

```php
if (!function_exists('formatNameForCertificate')) {
    function formatNameForCertificate(
        string $firstName,
        string $middleName,
        string $lastName,
        string $extension = ''
    ): string {
        $name = strtoupper(trim($lastName)) . ', ' . strtoupper(trim($firstName));

        if (!empty(trim($middleName))) {
            $name .= ' ' . strtoupper(substr(trim($middleName), 0, 1)) . '.';
        }

        if (!empty(trim($extension))) {
            $name .= ' ' . strtoupper(trim($extension));
        }

        return $name;
    }
}
```

**Registered in `composer.json`:**
```json
"autoload": {
    "files": [
        "app/helpers.php"
    ]
}
```

Run after editing `composer.json`:
```bash
composer dump-autoload
```

---

## Files Modified

- `app/helpers.php` — function added
- `composer.json` — `app/helpers.php` added to `autoload.files`
- All certificate Blade templates — removed inline `@php` function definitions

---

## Notes

This pattern (global helpers with `function_exists` guards) applies to any utility function shared across multiple Blade views. Never define PHP functions inside `@php` blocks in templates.
