# System Architecture Diagram

## Overview

GMAMS and the Clearance Management System (CMS) are two separate Laravel applications sharing a single MySQL database. Bidirectional synchronization keeps user records and profile data consistent between them.

---

## Deployment Topology

```
┌─────────────────────────────────────────────────────────────┐
│  Shared Database:  db-clearance-system  (MySQL 8, port 3306)│
└──────────────────────┬──────────────────────────────────────┘
                       │
           ┌───────────┴───────────┐
           │                       │
┌──────────▼──────────┐  ┌─────────▼─────────────┐
│  GMAMS              │  │  CMS                   │
│  Good Moral App     │  │  Clearance Mgmt System │
│                     │  │                        │
│  Port: 8000         │  │  Port: 8001            │
│  (or Herd / nginx)  │  │                        │
│                     │  │                        │
│  PHP 8.4            │  │  PHP 8.x               │
│  Laravel 12         │  │  Laravel 11+           │
└──────────┬──────────┘  └─────────┬──────────────┘
           │                       │
           │ ◄── ClearanceSyncService (GMAMS → CMS)
           │ ──► GoodMoralSyncService (CMS → GMAMS)
           └───────────┬───────────┘
                       │
                 Bidirectional Sync
```

---

## Database Tables

### GMAMS Tables

| Table | Purpose |
|-------|---------|
| `users` | Authentication (id, name, email, password) |
| `student_registrations` | Student profiles (fname, lname, student_id, department, etc.) |
| `role_account` | Role assignments (account_type, department, course, status) |
| `good_moral_applications` | Certificate applications |
| `good_moral_certificates` | Generated certificate records |
| `student_violations` | Violation records (type, offense, department, academic year) |
| `notifications` | In-app notifications |
| `courses` | Imported course master data |

### CMS Tables

| Table | Purpose |
|-------|---------|
| `users` (clearance_users) | Extended user profiles |
| `students` | Student enrollment records |
| `clearances` | Department clearance records |
| `academic_years` | Academic year configurations |
| `departments` | Department definitions |

---

## Sync Flow

### GMAMS → CMS (`ClearanceSyncService`)

Triggered when:
- A student registers in GMAMS
- A student updates their profile in GMAMS (name, email, password)

```
GMAMS Registration
  ├── users
  ├── student_registrations
  ├── role_account
  └── ClearanceSyncService::syncUser()
          ├── clearance_users (CMS)
          ├── students (CMS)
          └── clearances (CMS)
```

### CMS → GMAMS (`GoodMoralSyncService`)

Triggered when:
- A user registers in CMS

```
CMS Registration
  ├── users (CMS)
  ├── students (CMS)
  └── GoodMoralSyncService::syncToClearance()
          ├── users (GMAMS)
          ├── student_registrations (GMAMS)
          └── role_account (GMAMS)
```

---

## Application Request Flow

```
Browser
  │
  ▼
nginx (Laravel Herd / production nginx)
  │
  ▼
PHP-CGI / PHP-FPM
  │
  ▼
Laravel Router (routes/web.php)
  │
  ├── Middleware: auth, role:*
  │
  ▼
Controller (AdminController, ApplicationController, etc.)
  │
  ├── Model (Eloquent)
  ├── Service (ClearanceSyncService, GoodMoralSyncService)
  │
  ▼
View (Blade template + Tailwind CSS)
  │
  ▼
Browser Response
```

---

## Technology Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 12.9.0 |
| PHP | 8.4.x |
| Database | MySQL 8 |
| Frontend | Blade templates, Tailwind CSS |
| Icons | Heroicons (`<x-icon>` component) |
| PDF generation | wkhtmltopdf (via Laravel PDF package) |
| Email | Gmail SMTP / Laravel Mail |
| Local dev server | Laravel Herd (Windows) |
| Session storage | Database (`sessions` table) |
| File storage | Local disk (`storage/app/public`) |
