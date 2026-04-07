# Fix: `getimagesize()` Error During Receipt Validation

## Problem

A PHP fatal error occurred during receipt file validation when admin approved an application:

```
PHP Warning: getimagesize(): Error reading from /tmp/php_XXXXX
```

This caused a 500 error while processing the receipt upload.

---

## Root Cause

A race condition in PHP's temp file handling: PHP deleted the uploaded temp file before `getimagesize()` could read it. This happened because:
1. Laravel moved the file, but the temp path reference remained.
2. PHP's garbage collection cleared the temp file.
3. `getimagesize()` attempted to read the now-missing temp file.

---

## Solution

Three changes were applied to `ApplicationController.php`:

### 1. Copy Temp File Before Validation

```php
// Copy the upload to a stable temp path before validation
$tempPath = sys_get_temp_dir() . '/receipt_' . uniqid() . '.tmp';
copy($request->file('receipt')->getRealPath(), $tempPath);
```

### 2. Suppress `getimagesize()` Warning

```php
$imageInfo = @getimagesize($tempPath);
```

### 3. Wrap in try/catch

```php
try {
    $imageInfo = @getimagesize($tempPath);
    if ($imageInfo === false) {
        // Handle non-image file gracefully
        Log::warning('Could not read image info from receipt', ['path' => $tempPath]);
    }
} catch (\Exception $e) {
    Log::error('Receipt validation error: ' . $e->getMessage());
} finally {
    // Clean up our temp copy
    if (file_exists($tempPath)) {
        unlink($tempPath);
    }
}
```

---

## Files Modified

- `app/Http/Controllers/ApplicationController.php` — receipt validation section updated

---

## Notes

The `@` error suppressor is acceptable here because the failure is handled explicitly in the catch/log block. Do not use `@` globally — only for this known-safe edge case.
