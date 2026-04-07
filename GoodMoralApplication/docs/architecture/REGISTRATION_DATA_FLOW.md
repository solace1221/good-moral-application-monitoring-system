# Registration Data Flow

## Overview

This document describes the flow of student registration data between the Clearance Management System (CMS) and GMAMS.

---

## Flow Diagram

```
CMS (clearance_users table)
        |
        | GoodMoralSyncService::syncUsers()
        v
GMAMS (users table)
        |
        | ClearanceSyncService::updateUser()
        v
CMS (clearance_users table)  ← profile updates pushed back
```

---

## Step-by-Step Flow

### 1. New Student Registers in CMS

- Student completes enrollment registration in CMS.
- Record created in `clearance_users` (CMS database).

### 2. GMAMS Pulls from CMS

- `GoodMoralSyncService::syncUsers()` is triggered (scheduled or manual).
- Fetches new/updated records from `clearance_users`.
- Creates or updates matching records in GMAMS `users` table.
- Role is set to `student` by default.

### 3. Student Logs into GMAMS

- Student uses the same email/password registered in CMS.
- Authentication is handled against the GMAMS `users` table.

### 4. Student Updates Profile in GMAMS

- Student edits name, email, or password.
- `ClearanceSyncService::updateUser()` pushes the change back to `clearance_users`.

---

## Database Columns Synced

| GMAMS `users` | CMS `clearance_users` |
|---|---|
| `name` | `name` |
| `email` | `email` |
| `password` | `password` |
| `student_id` | `student_id` |
| `course` | `course` |
| `college` | `college` |

---

## Related Files

- `app/Services/GoodMoralSyncService.php`
- `app/Services/ClearanceSyncService.php`
- [ACCOUNT_SYNC_IMPLEMENTATION.md](../setup/ACCOUNT_SYNC_IMPLEMENTATION.md)
- [DATA_SYNC_ANALYSIS_AND_SOLUTION.md](DATA_SYNC_ANALYSIS_AND_SOLUTION.md)
