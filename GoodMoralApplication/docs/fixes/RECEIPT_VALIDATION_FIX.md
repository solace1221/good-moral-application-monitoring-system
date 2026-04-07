# Fix: Receipt File Upload Validation

## Problem

When students uploaded a receipt image during application submission, a PHP error occurred during file validation:

```
PHP Warning: getimagesize(): Error reading from /tmp/php_XXXXX
```

This caused a 500 error and the application submission failed.

---

## Root Cause

PHP deleted the uploaded temp file between the time the file handle was opened and the time `getimagesize()` attempted to read it. This is a race condition in PHP's temp file lifecycle.

---

## Solution

Three changes in `ApplicationController.php`:

### 1. Copy temp file to a stable path

```php
$uploadedFile = $request->file('receipt');
$stableTempPath = sys_get_temp_dir() . '/receipt_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
copy($uploadedFile->getRealPath(), $stableTempPath);
```

### 2. Use the stable path for validation

```php
$imageInfo = @getimagesize($stableTempPath);
```

### 3. Clean up and handle errors

```php
try {
    if ($imageInfo === false) {
        throw new \RuntimeException('Could not read uploaded file as image.');
    }
    // Proceed with storage
    $uploadedFile->storeAs('receipts', $filename, 'public');
} catch (\Exception $e) {
    Log::error('Receipt validation failed: ' . $e->getMessage());
    return back()->withErrors(['receipt' => 'Invalid file. Please upload a valid image (JPG, PNG).']);
} finally {
    if (file_exists($stableTempPath)) {
        unlink($stableTempPath);
    }
}
```

---

## Files Modified

- `app/Http/Controllers/ApplicationController.php` — receipt upload validation section

---

## Accepted File Types

- JPEG / JPG
- PNG
- Maximum size: 2 MB (enforced by Laravel validation rule `max:2048`)

---

## Testing

1. Submit an application with a valid JPG receipt.
2. Submit with a valid PNG receipt.
3. Submit with an invalid file type (e.g., `.txt`) → expect validation error message.
4. Submit with no file → expect "receipt is required" error.
