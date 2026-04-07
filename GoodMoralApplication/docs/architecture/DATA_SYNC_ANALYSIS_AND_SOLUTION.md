# Data Synchronization Analysis and Solution

## Problem Statement

Users who registered in the Clearance Management System (CMS) were not appearing in GMAMS. This caused failures when those students attempted to apply for a Good Moral Certificate.

---

## Current Architecture

Both systems use a **shared database** (`db-clearance-system`, MySQL on localhost:3306).

### Table Mapping

**GMAMS Tables**

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `users` | Authentication | id, name, email, password |
| `student_registrations` | Student data | fname, lname, email, student_id, department, year_level, account_type |
| `role_account` | Role assignments | fullname, email, student_id, department, account_type, status |

**CMS Tables**

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `users` | Extended profiles | firstname, lastname, middlename, email, password, role, department_id, course_id |
| `students` | Enrollment records | users_id, student_number, department_id, course_id, year, academic_id |
| `clearances` | Clearance tracking | student_id, academic_id, department_id, overall_status |

---

## Root Cause Analysis

### Working Direction: GMAMS → CMS

When a user registered in GMAMS, `ClearanceSyncService::syncUser()` was called and successfully wrote to CMS tables.

### Missing Direction: CMS → GMAMS

When a user registered in CMS, the CMS `RegisteredUserController` had **no sync call**. As a result:
- No record was created in `student_registrations`
- No record was created in `role_account`
- The student existed in CMS auth but was invisible to GMAMS

### Data Transformation Complexity

The two systems use different field structures:

| Field | CMS Format | GMAMS Format |
|-------|-----------|-------------|
| Name | `firstname`, `lastname`, `middlename` (separate) | `fname`, `lname`, `mname` + `fullname` (combined) |
| Department | Integer `department_id` | String code (e.g., `SITE`) |
| Course | Integer `course_id` | String code (e.g., `BSIT`) |
| Year level | Integer `year` | String (e.g., `1st Year`) |

---

## Solution: Bidirectional Event-Based Sync

### New Service: `GoodMoralSyncService`

**File**: `app/Services/GoodMoralSyncService.php`

Handles CMS → GMAMS transformation and insertion:

```php
public function syncToGoodMoral(CmsUser $cmsUser, CmsStudent $cmsStudent): void
{
    $department = $this->departmentMap[$cmsStudent->department_id] ?? 'UNKNOWN';
    $course     = $this->courseMap[$cmsStudent->course_id] ?? '';
    $yearLevel  = $this->yearLevelMap[$cmsStudent->year] ?? '1st Year';

    // Create or update GMAMS user
    $gmamsUser = User::updateOrCreate(
        ['email' => $cmsUser->email],
        [
            'name'     => $cmsUser->firstname . ' ' . $cmsUser->lastname,
            'password' => $cmsUser->password,
        ]
    );

    // Create or update student_registrations
    StudentRegistration::updateOrCreate(
        ['email' => $cmsUser->email],
        [
            'fname'        => $cmsUser->firstname,
            'lname'        => $cmsUser->lastname,
            'mname'        => $cmsUser->middlename,
            'department'   => $department,
            'course'       => $course,
            'year_level'   => $yearLevel,
            'account_type' => 'student',
        ]
    );

    // Create or update role_account
    RoleAccount::updateOrCreate(
        ['email' => $cmsUser->email],
        [
            'fullname'     => $cmsUser->firstname . ' ' . $cmsUser->lastname,
            'department'   => $department,
            'account_type' => 'student',
            'status'       => 'active',
        ]
    );
}
```

### Updated: CMS `RegisteredUserController`

```php
public function store(Request $request): RedirectResponse
{
    // ... existing registration logic ...

    // Sync to GMAMS
    try {
        $syncService = new GoodMoralSyncService();
        $syncService->syncToGoodMoral($user, $student);
    } catch (\Exception $e) {
        Log::error('CMS → GMAMS sync failed: ' . $e->getMessage());
        // Registration still succeeds; sync is non-blocking
    }

    return redirect()->route('dashboard');
}
```

---

## Department and Course ID Maps

| `department_id` | GMAMS code |
|-----------------|-----------|
| 1 | SITE |
| 2 | SBAHM |
| 3 | SNAHS |
| 4 | SASTE |

| `course_id` | course code |
|-------------|------------|
| 1 | BSIT |
| 2 | BSCS |
| 3 | BSN |
| 4 | BSBA |
| (etc.) | (etc.) |

---

## Related Files

| File | Description |
|------|-------------|
| `app/Services/GoodMoralSyncService.php` | New CMS → GMAMS sync service |
| `app/Services/ClearanceSyncService.php` | Existing GMAMS → CMS sync service |
| `batch_sync_cms_to_gmams.php` | Batch sync utility for existing users |
| `test_bidirectional_sync.php` | Automated test suite |
