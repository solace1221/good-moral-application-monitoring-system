# Fix: Certificate Reprint Button

## Problem

Clicking the **Reprint** button for a certificate in `Ready for Pickup` status returned an error or did nothing. Reprinting a certificate was impossible after the first print.

---

## Root Cause

`AdminController::printCertificate()` had a strict status check that only allowed printing when `application_status === 'Approved by Administrator'`. After the first print, the status changes to `Ready for Pickup`, which caused the status check to reject the reprint request.

---

## Solution

### 1. Updated Status Check

Changed the single-status check to `in_array()` accepting both valid print statuses:

```php
// Before
if ($application->application_status !== 'Approved by Administrator') {
    return redirect()->back()->with('error', 'Certificate can only be printed for applications approved by administrator!');
}

// After
if (!in_array($application->application_status, ['Approved by Administrator', 'Ready for Pickup'])) {
    return redirect()->back()->with('error', 'Certificate can only be printed for approved applications.');
}
```

### 2. Reprint Detection

```php
$isReprint = $application->application_status === 'Ready for Pickup';
```

### 3. Filename Suffix for Reprints

```php
$reprintSuffix = $isReprint ? '_REPRINT' : '';
$filename = "{$certificateType}_Certificate_{$application->student_id}_{$application->reference_number}{$reprintSuffix}.pdf";
```

### 4. Conditional Logging and Notifications

```php
if (!$isReprint) {
    // First print: update status, notify student
    $application->update(['application_status' => 'Ready for Pickup']);
    // Create student notification
    Log::info("First print: status updated to Ready for Pickup");
} else {
    // Reprint: no status change, no new notification
    Log::info("Reprint: status and notification unchanged");
}
```

---

## Files Modified

- `app/Http/Controllers/AdminController.php` — `printCertificate()` method (lines ~1470–1590)

---

## Testing

1. Approve an application as Admin.
2. Click **Print Certificate** — PDF downloads, status changes to `Ready for Pickup`.
3. Click **Reprint** — PDF downloads with `_REPRINT` suffix, status remains `Ready for Pickup`.
4. No duplicate notification sent to student on reprint.
