# Backend Workflow Audit Report

**Scope**: Admin (Head OSA) & Sec OSA (Moderator) Dashboards  
**Date**: June 2025  
**Type**: Inspection — read-only analysis, no code changes

---

## Table of Contents

1. [Dashboard Data Sources](#1-dashboard-data-sources)
2. [Role Permissions & Middleware](#2-role-permissions--middleware)
3. [Violation Workflow](#3-violation-workflow)
4. [Good Moral Workflow](#4-good-moral-workflow)
5. [Notification Logic](#5-notification-logic)
6. [Performance Concerns](#6-performance-concerns)
7. [Bugs & Inconsistencies](#7-bugs--inconsistencies)
8. [Recommendations](#8-recommendations)

---

## 1. Dashboard Data Sources

### Admin Dashboard (`Admin\DashboardController::dashboard`)

| Data Card | Source | Query |
|---|---|---|
| Application counts by dept | `DashboardStatsService::getApplicationCountsByDepartment($frequency)` | Individual query per department (6 departments) |
| Violation stats | `DashboardStatsService::getViolationStats($frequency)` | 4 queries: minor pending/resolved + major pending/resolved |
| Violations by department | `DashboardStatsService::getViolationsByDepartment($frequency)` | 2 queries × 6 departments = 12 queries |
| Violation table | `Violation::paginate(10)` | Paginates the `violations` (catalog) table, NOT `student_violations` |
| Escalation notifications | `ViolationNotif::where('student_id', $admin->student_id)->where('ref_num', 'LIKE', 'ESCALATION-%')` | Filtered by admin's student_id |
| Trends data | `TrendsAnalysisService::getMajorOffenseTrends($frequency)` | Major offense trends for charts |
| Minor offenses data | `TrendsAnalysisService::getMinorOffensesData($frequency)` | Minor offense data for charts |

**Note**: The frequency parameter is correctly passed from the request in the Admin dashboard.

### Sec OSA Dashboard (`SecOSA\DashboardController::dashboard`)

| Data Card | Source | Query |
|---|---|---|
| Application counts by dept | `DashboardStatsService::getApplicationCountsByDepartment()` | **No frequency parameter passed** — always shows 'all' |
| Violation stats | `DashboardStatsService::getViolationStats()` | **No frequency parameter passed** — always shows 'all' |
| Violations by department | `DashboardStatsService::getViolationsByDepartment()` | **No frequency parameter passed** — always shows 'all' |
| Pending applications | `SecOSAApplication::where('status', 'pending')` | Legacy SecOSA applications |
| Print-ready applications | `GoodMoralApplication::readyForPrint()` | New system ready-for-print apps |
| Escalation notifications | `ViolationNotif::where('notif', 'LIKE', '%3 minor violations%')->orWhere(...)` | **Fragile text-matching** on notification text |
| Trends data | `TrendsAnalysisService::getMajorOffenseTrends('monthly')` | Hardcoded 'monthly' |
| Minor offenses data | `TrendsAnalysisService::getMinorOffensesData('monthly')` | Hardcoded 'monthly' |

---

## 2. Role Permissions & Middleware

### Middleware Stack

| Middleware | Alias | Behavior |
|---|---|---|
| `AdminOnly` | `'admin'` | Checks `Auth::user()->account_type !== 'admin'` + status === 'active' |
| `RoleMiddleware` | `'role'` | Checks `Auth::user()->account_type` against variadic `$roles` + status === 'active' |
| `SecureFileAccess` | `'secure.file'` | Directory traversal prevention, file-type validation |
| `SecurityHeaders` | (global append) | X-Frame-Options, CSP, HSTS, etc. |

### Field Access — RESOLVED (Not a Bug)

The middleware checks `$user->account_type` but the `users` table only has a `role` column. This works because `User.php` has an accessor:

```php
public function getAccountTypeAttribute() {
    return $this->role;
}
```

`Auth::user()->account_type` correctly maps to `$this->role`. **No bug here.**

### Route Protection

| Route Group | Middleware | Count | Correct? |
|---|---|---|---|
| Admin routes | `['auth', 'verified', 'admin']` | ~40 routes | ✅ Yes |
| Sec OSA routes | `['auth', 'verified', 'role:sec_osa']` | ~16 routes | ✅ Yes |
| Shared API (student search) | `['auth', 'role:admin,sec_osa,psg_officer']` | — | ✅ Yes |

### Permission Separation

- Sec OSA has **no routes** for account management, departments, courses, organizations, or positions — properly restricted to the Admin role.
- Sec OSA **can**: view/upload violation proceedings, forward to admin, print certificates.
- Admin **can**: full CRUD on violations, accounts, applications, PSG management, reporting.

### Additional Guard: RoleCheck Trait

Used in Sec OSA controllers as a secondary check in constructors:
```php
$this->checkRole(['sec_osa']);
```
Reads `Auth::user()->account_type` and aborts 403 if mismatch. Redundant with RoleMiddleware but adds defense-in-depth. **Not harmful.**

---

## 3. Violation Workflow

### Status Codes

| Status | Meaning | Set By |
|---|---|---|
| `'0'` | Pending — newly created | System (on creation) |
| `'1'` | Proceedings uploaded / Dean approved | Sec OSA (upload) or Dean (violation approve) |
| `'1.5'` | Forwarded to Admin | Sec OSA (forward action) |
| `'2'` | Closed / Resolved | Admin (close case) |

### Minor Violation Flow

```
Student registered → status='0' (Sec OSA views)
  → Sec OSA uploads document → status='1' + case number assigned
  → Every 3 minors: auto-escalation creates a major violation
```

### Major Violation Flow

```
Student registered → status='0'
  → Sec OSA uploads proceedings → status='1'
  → Sec OSA forwards to admin → status='1.5'
  → Admin closes case → status='2'
```

### Escalation Logic (`ViolationEscalationTrait`)

- Triggers at multiples of 3 minor violations (3, 6, 9, …).
- Creates a new major violation with `ref_num = 'ESCALATION-YYYYMMDD-XXXXXX'`.
- Checks for duplicate escalation before creating.
- Notifies: all admins (via their `student_id`), the student, and department program coordinators.

### Escalation Concern

The auto-created major violation sets:
```php
'course' => $student->year_level ?? 'Unknown'
```
This maps `year_level` into the `course` field — **incorrect field mapping** (see Bug #4 below).

---

## 4. Good Moral Workflow

### Full Application Pipeline

```
Student applies (GoodMoral\ApplicationController::applyForGoodMoralCertificate)
  → Creates GoodMoralApplication (status='pending', application_status=null)
  → Notification status '0' (submitted)

Registrar approves (no controller found — may be external or missing)
  → application_status = 'Approved By Registrar ...'
  → Notification status '1'

Dean approves (Dean\ApplicationController::approveGoodMoral)
  → GoodMoralWorkflowService::approveByDean()
  → status = 'waiting_for_payment'
  → application_status = 'Approved by Dean: {name} - Waiting for Payment'
  → Generates payment notice via ReceiptService
  → Notification status '3'

Student uploads receipt (GoodMoral\ApplicationController::upload)
  → GoodMoralWorkflowService::handleReceiptUpload()
  → status = 'receipt_uploaded'
  → application_status = 'Receipt Uploaded - Pending Admin Approval'
  → Creates HeadOSAApplication for admin review
  → Notification status '4'

Admin approves (Admin\ApplicationController::approveGoodMoralApplication)
  → GoodMoralWorkflowService::approveByAdmin()
  → status = 'approved'
  → application_status = 'Ready for Moderator Print'
  → Also approves HeadOSAApplication
  → Notification status '2'

Sec OSA prints certificate (SecOSA\CertificateController::printCertificate)
  → CertificateService::generateCertificate()
  → On first print: application_status = 'Ready for Pickup'
  → Notification status '5'
```

### Legacy Pipeline (Still Active)

A parallel pipeline exists using `DeanApplication → HeadOSAApplication → SecOSAApplication`. Legacy methods are marked with `// TODO: Legacy method - review for removal later`. Both legacy and new-system applications appear in the Admin dashboard.

### Certificate Types

- **Good Moral Certificate**: For students/alumni with no unresolved violations.
- **Certificate of Residency**: For students/alumni with violations.
- View selection is handled by `CertificateService::determineCertificateView()`.

### Registrar Controller

**No Registrar controller directory was found** at `app/Http/Controllers/Registrar/`. The registrar approval step appears to happen outside this codebase or is handled by a different mechanism. `NotificationCountService::getRegistrarCounts()` references `GoodMoralApplication::where('status', 'pending')` but there's no controller to serve it.

---

## 5. Notification Logic

### Notification Tables

| Table | Purpose | Key Fields |
|---|---|---|
| `notif_archives` | Application workflow notifications | `student_id`, `reference_number`, `status`, `application_status` |
| `violation_notifs` | Violation & escalation notifications | `student_id`, `ref_num`, `status`, `notif` |

### Application Notification Statuses

| Status | Meaning |
|---|---|
| `'0'` | Application submitted |
| `'1'` | Registrar approved |
| `'2'` | Admin approved (ready for print) |
| `'3'` | Dean approved (waiting for payment) |
| `'4'` | Receipt uploaded (pending admin review) |
| `'5'` | Certificate printed (ready for pickup) |
| `'-1'` | Rejected by Registrar |
| `'-2'` | Rejected by Admin |
| `'-3'` | Rejected by Dean |

### Escalation Notification Delivery

Admin escalation notifications are sent to each admin's `student_id` in the `violation_notifs` table. The admin dashboard then queries:
```php
ViolationNotif::where('student_id', $currentAdmin->student_id)
    ->where('ref_num', 'LIKE', 'ESCALATION-%')
```

**Problem**: If an admin account has no `student_id` (which is possible for non-student staff accounts), they will never see escalation notifications. The trait guards against this with `if ($admin->student_id)` but silently skips those admins.

### Sec OSA Escalation Query — Fragile

The Sec OSA dashboard uses text-matching to find escalation notifications:
```php
ViolationNotif::where('notif', 'LIKE', '%3 minor violations%')
    ->orWhere('notif', 'LIKE', '%AUTOMATIC ESCALATION%')
```
This is fragile — if the notification text changes, escalation alerts break. The Admin dashboard uses `ref_num LIKE 'ESCALATION-%'` which is more robust.

---

## 6. Performance Concerns

### N+1 Query Patterns

| Location | Issue | Impact |
|---|---|---|
| `ViolationService::getAllEscalationData()` | Gets distinct students, then loops with individual queries per student | O(n) queries where n = students with minor violations |
| `ViolationService::getEscalationNotificationsList()` | Gets escalated students, then loops each to fetch violations + auto-major | O(2n) queries where n = students with 3+ minors |
| `DashboardStatsService::getViolationsByDepartment()` | 2 queries per department × 6 departments | 12 queries per page load (fixed, but avoidable) |
| `DashboardStatsService::getApplicationCountsByDepartment()` | 1 query per department × 6 departments | 6 queries per page load (fixed, but avoidable) |

### Heavy Dashboard Load

The Admin dashboard makes approximately **30+ queries** per page load:
- 6 dept application counts
- 4 violation stat queries
- 12 violation-by-department queries
- 1 Violation model paginate
- 1 escalation notification query
- 2+ TrendsAnalysisService queries
- Various other supporting queries

### Missing Indexes (Potential)

The following frequently-queried columns should have indexes:
- `student_violations.offense_type` (used in every violation stat query)
- `student_violations.status` (used in every pending/resolved filter)
- `student_violations.department` (used in department breakdown queries)
- `good_moral_applications.application_status` (used in LIKE queries)
- `violation_notifs.student_id` + `ref_num` (composite, for escalation lookups)

---

## 7. Bugs & Inconsistencies

### Bug #1 — CRITICAL: PSG Application Count Uses Obsolete Status Value

**File**: `app/Services/NotificationCountService.php` line 22  
**Code**:
```php
'psgApplications' => RoleAccount::where('account_type', 'psg_officer')
    ->where('status', '5')
    ->count(),
```
**Problem**: The status field was changed from numeric values to `'active'`/`'inactive'` strings in a prior phase. `status='5'` will never match anything. The Admin sidebar PSG badge will always show 0.  
**Fix**: Determine what PSG applications should be counted (likely pending PSG applications, not accounts with a specific status).

### Bug #2 — MEDIUM: Sec OSA Dashboard Ignores Frequency Filter

**File**: `app/Http/Controllers/SecOSA/DashboardController.php` lines 41, 49, 59  
**Code**:
```php
$frequency = request()->get('frequency', 'monthly'); // Reads frequency from request
$deptCounts = $this->statsService->getApplicationCountsByDepartment(); // But doesn't pass it
$vStats = $this->statsService->getViolationStats();                   // Same
$deptViolations = $this->statsService->getViolationsByDepartment();   // Same
```
**Problem**: The frequency UI exists on the page (frequencyOptions is passed to the view), but the selected frequency is never applied to the data queries. All stats always show 'all' data regardless of user selection.  
**Fix**: Pass `$frequency` to each service method call.

### Bug #3 — MEDIUM: Admin Escalation Notifications May Be Empty  

**File**: `app/Http/Controllers/Admin/DashboardController.php` line 93  
**Code**:
```php
$escalationNotifications = ViolationNotif::where('student_id', $currentAdmin->student_id)
```
**Problem**: Admin accounts are staff — they may not have a `student_id` in the `role_account` table. The `User::getStudentIdAttribute()` accessor returns `$this->roleAccount?->student_id` which would be `null` for staff-only accounts. A `WHERE student_id = NULL` query returns no results.  
**Impact**: Admins without a student_id in role_account will never see escalation notifications on their dashboard.  
**Root Cause**: The `ViolationEscalationTrait::notifyAdminOfEscalation()` skips creating notifications for admins without student_id (`if ($admin->student_id)`), so the data is also not created.  
**Fix**: Use a different mechanism for admin notifications (e.g., a dedicated admin notifications table, or use the user ID instead of student_id).

### Bug #4 — LOW: Escalation Trait Maps year_level to course Field

**File**: `app/Traits/ViolationEscalationTrait.php` line 74  
**Code**:
```php
'course' => $student->year_level ?? 'Unknown',
```
**Problem**: The auto-escalated major violation stores the student's year_level (e.g., "3rd Year") in the `course` field (which expects a course code like "BSIT").  
**Fix**: Use `$student->course ?? 'Unknown'`.

### Bug #5 — LOW: DashboardStatsService "Pending" Includes Intermediate Statuses

**File**: `app/Services/DashboardStatsService.php` line 150  
**Code**:
```php
if ($statusCondition === 'pending') {
    $query->where('status', '!=', 2);
}
```
**Problem**: This counts statuses '0', '1', and '1.5' all as "pending." In reality, '1' means proceedings uploaded and '1.5' means forwarded to admin — neither are truly "pending." The dashboard stats overcount pending violations.  
**Impact**: Dashboard stats may be misleading to administrators.

### Bug #6 — LOW: VIOLATION_DEPARTMENTS Constant Unused by Default

**File**: `app/Services/DashboardStatsService.php` lines 22, 188  
**Code**:
```php
public const VIOLATION_DEPARTMENTS = ['SITE', 'SASTE', 'SBAHM', 'SNAHS'];
// But getViolationsByDepartment defaults to self::DEPARTMENTS (all 6)
```
**Problem**: `VIOLATION_DEPARTMENTS` excludes SOM and GRADSCH (presumably because those programs don't track violations the same way), but `getViolationsByDepartment()` defaults to all 6 departments. The constant is defined but not used.

### Issue #7 — INFO: Duplicate Approve Methods in Admin ApplicationController

**File**: `app/Http/Controllers/Admin/ApplicationController.php`  
Two methods do nearly the same thing:
- `approveGoodMoralApplication()` — handles the new GoodMoralApplication system
- `approveApplication()` — may handle legacy HeadOSAApplication

Both are routed. Not a bug per se, but increases maintenance surface. The legacy methods should eventually be removed.

### Issue #8 — INFO: Legacy SecOSA Certificate Methods

**File**: `app/Http/Controllers/SecOSA/CertificateController.php`  
Legacy `approve()`/`reject()` methods exist with `// TODO: Legacy method - review later` comments. These may still have active routes.

### Issue #9 — INFO: Missing Registrar Controller

No controller exists at `app/Http/Controllers/Registrar/`. The `NotificationCountService::getRegistrarCounts()` method exists, and routes likely exist for registrar, but the controller was not found during this audit.

---

## 8. Recommendations

### Priority: HIGH

1. **Fix PSG notification count** (Bug #1): Update the query in `NotificationCountService::getAdminCounts()` to use the correct status value or logic for counting pending PSG applications.

2. **Fix Sec OSA frequency filter** (Bug #2): Pass `$frequency` to `getApplicationCountsByDepartment()`, `getViolationStats()`, and `getViolationsByDepartment()` in the Sec OSA DashboardController.

3. **Fix admin escalation notification delivery** (Bug #3): Decouple admin notifications from `student_id`. Options:
   - Create notifications using the admin's user ID instead
   - Use a dedicated admin notification mechanism
   - Ensure all admin accounts have a student_id in role_account

### Priority: MEDIUM

4. **Fix course field in escalation** (Bug #4): Change `$student->year_level` to `$student->course` in `ViolationEscalationTrait::createEscalatedMajorViolation()`.

5. **Refine violation status reporting** (Bug #5): Break "pending" into sub-categories (e.g., "New", "In Progress", "Forwarded") so dashboard stats are more meaningful.

6. **Standardize Sec OSA escalation query** (Section 5): Replace the fragile `LIKE '%3 minor violations%'` text search with `ref_num LIKE 'ESCALATION-%'` matching the Admin pattern.

### Priority: LOW

7. **Reduce dashboard query count**: Consolidate `getViolationsByDepartment()` and `getApplicationCountsByDepartment()` to use single grouped queries instead of per-department loops.

8. **Fix N+1 in ViolationService**: Use eager loading in `getAllEscalationData()` and `getEscalationNotificationsList()`.

9. **Use VIOLATION_DEPARTMENTS** (Bug #6): Apply `VIOLATION_DEPARTMENTS` as the default in `getViolationsByDepartment()` or remove the unused constant.

10. **Plan legacy cleanup**: Schedule removal of legacy `DeanApplication → HeadOSAApplication → SecOSAApplication` pipeline and associated TODO methods once the new pipeline is stable.

11. **Locate or create Registrar controller** (Issue #9): Verify where registrar approval happens and ensure it's documented.

---

## Summary

| Category | Status |
|---|---|
| Middleware & Route Protection | ✅ Correct — properly aliased, accessor resolves field mapping |
| Violation Workflow | ✅ Functional — minor data-mapping bugs in escalation |
| Good Moral Workflow | ✅ Functional — full pipeline works; legacy pipeline coexists |
| Notification Logic | ⚠️ Admin escalation delivery unreliable if admin has no student_id |
| Dashboard Data Accuracy | ⚠️ Sec OSA frequency filter broken; PSG count broken; "pending" overcount |
| Performance | ⚠️ ~30+ queries per admin dashboard load; N+1 patterns in escalation service |
| Security | ✅ No authorization bypasses found; file-access and headers are solid |
