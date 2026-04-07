# Account Sync — Technical Implementation Guide

## Overview

When a student registers in GMAMS, their account is automatically synchronized to the Clearance Management System (CMS). This document describes the technical implementation.

---

## Sync Service

**File**: `app/Services/ClearanceSyncService.php`

### `syncUser()` Method

Called immediately after a student registers in GMAMS.

**Flow:**
```
Registration in GMAMS
    ├── Create record in users
    ├── Create record in student_registrations
    ├── Create record in role_account
    └── ClearanceSyncService::syncUser()
            ├── Create/update record in clearance_users (CMS users table)
            ├── Create record in students (CMS)
            └── Create record in clearances (CMS)
```

### `updateUser()` Method

Called when a student updates their profile in GMAMS.

**Syncs:**
- `firstname`, `middlename`, `lastname`, `extension`
- `email`
- `password` (hashed)

---

## Data Field Mappings

| GMAMS Field | CMS Field |
|-------------|-----------|
| `student_registrations.fname` | `clearance_users.firstname` |
| `student_registrations.lname` | `clearance_users.lastname` |
| `student_registrations.mname` | `clearance_users.middlename` |
| `users.email` | `clearance_users.email` |
| `users.password` | `clearance_users.password` |
| `student_registrations.department` | `students.department_id` (via lookup) |
| `student_registrations.course` | `students.course_id` (via lookup) |
| `student_registrations.year_level` | `students.year` |

---

## Error Handling

All sync calls are wrapped in try/catch blocks. If the sync fails:
- The primary registration in GMAMS still succeeds.
- The error is logged to `storage/logs/laravel.log`.
- No exception is thrown to the user.

---

## Testing

```bash
php artisan tinker

# Test sync manually for a specific user
>>> $service = new App\Services\ClearanceSyncService();
>>> $user = App\Models\User::find(1);
>>> $service->syncUser($user);
```

Check `storage/logs/laravel.log` for confirmation.
