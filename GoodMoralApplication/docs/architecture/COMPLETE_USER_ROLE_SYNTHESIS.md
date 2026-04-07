# Complete User Role Synthesis

## Overview

GMAMS implements a seven-role access control system. Each role has a distinct set of permissions and a dedicated dashboard.

---

## Role Definitions

### 1. `admin`

**Description**: Full system administrator.

**Access:**
- All application records (view, approve, reject)
- Certificate printing (first print and reprint)
- Violation management (all departments)
- Trends analysis dashboard
- User management (create, edit, deactivate accounts)
- Course and year level management
- System settings and configuration

---

### 2. `moderator` / `secosa`

**Description**: OSA (Office of Student Affairs) staff — second-level approver.

**Access:**
- Review and approve/reject applications (after student submission)
- View all violations (all departments)
- Trends analysis dashboard (same data as Admin)
- Notification management
- Cannot print certificates (Admin only)

---

### 3. `dean`

**Description**: School dean for a specific department (SITE, SBAHM, SNAHS, SASTE).

**Access:**
- View violations for their department only
- Add and record violations for students in their department
- View student profiles in their department
- Application status visibility (read-only)

---

### 4. `deansom`

**Description**: Dean of Student Organization Management.

**Access:** Same routes as `dean`. Scope: student organization-related violations and student organization member records.

---

### 5. `deangradsch`

**Description**: Dean of Graduate School.

**Access:** Same routes as `dean`. Scope: graduate school students.

---

### 6. `progcoor`

**Description**: Program Coordinator.

**Access:**
- View and manage students in their assigned courses
- View violation summaries per course
- Cannot add violations directly (read-only for violations)

---

### 7. `student` / `alumni`

**Description**: Current student or SPUP graduate.

**Access:**
- Submit Good Moral Certificate application (with receipt upload)
- Track application status
- View own notifications
- Edit own profile (name, email, password)
- View own violation records (read-only)

---

### 8. `psg`

**Description**: PSG (Philippine Science Group) officer.

**Access:**
- Limited access to student organization data
- Cannot submit Good Moral applications themselves
- Can view PSG-relevant student records

---

## Role Middleware Reference

```php
// Apply in routes/web.php
Route::middleware(['auth', 'role:admin'])->group(...);
Route::middleware(['auth', 'role:moderator,secosa'])->group(...);
Route::middleware(['auth', 'role:dean,deansom,deangradsch'])->group(...);
Route::middleware(['auth', 'role:progcoor'])->group(...);
Route::middleware(['auth', 'role:student,alumni'])->group(...);
Route::middleware(['auth', 'role:psg'])->group(...);
```

---

## Dashboard Redirect Map

| `account_type` | Redirects to |
|----------------|-------------|
| `admin` | `/admin/dashboard` |
| `moderator`, `secosa` | `/secosa/dashboard` |
| `dean`, `deansom`, `deangradsch` | `/dean/dashboard` |
| `progcoor` | `/progcoor/dashboard` |
| `student`, `alumni` | `/student/dashboard` |
| `psg` | `/psg/dashboard` |
