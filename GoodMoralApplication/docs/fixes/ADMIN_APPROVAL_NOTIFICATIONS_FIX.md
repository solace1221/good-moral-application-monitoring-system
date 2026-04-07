# Fix: Admin Approval Notification Status

## Problem

When an admin approved or rejected an application, the notification status was not updating correctly. Notifications sent to students showed an incorrect status value, causing confusion in the student notification panel.

---

## Root Cause

The notification status field was being set to a raw string that did not match the expected enum/constant values used in the notification view. A mismatch between the status string in `AdminController` and the value checked in the notification Blade template caused notifications to appear with the wrong label.

---

## Solution

Updated `AdminController.php` to use the correct status constant when creating notifications:

```php
// Before
$notification->status = 'approved';

// After
$notification->status = 'Approved by Administrator';
```

The Blade template checks for `'Approved by Administrator'` — now the values match.

---

## Files Modified

- `app/Http/Controllers/AdminController.php` — notification `status` field updated

---

## Testing

1. Log in as Admin.
2. Approve an application.
3. Log in as the student.
4. Check the notification panel — status should read **Approved by Administrator**.
