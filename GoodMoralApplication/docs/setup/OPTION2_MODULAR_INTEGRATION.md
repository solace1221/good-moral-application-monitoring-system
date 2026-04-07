# Option 2: Modular Integration — Implementation Guide

## Overview

This guide covers the **Modular Integration** approach, where GMAMS and CMS run as separate, independent Laravel applications that share a database for seamless authentication.

---

## Architecture

| Application | URL | Database | Handles |
|-------------|-----|----------|---------|
| Good Moral Application (GMAMS) | `http://localhost:8000` | `db-clearance-system` | Applications, violations, certificates |
| Clearance Management System (CMS) | `http://localhost:8001` | `db-clearance-system` | Clearance records, department approvals |

Both applications connect to the **same MySQL database** (`db-clearance-system`), enabling shared authentication without duplicating user credentials.

---

## Benefits

- Complete separation of concerns between the two systems
- No database conflicts or table duplication between applications
- Each system maintains its own migrations and models
- Independent deployment possible
- Easy to maintain and update separately

---

## Setup

### Step 1: Configure Both `.env` Files

**GMAMS** (`.env`):
```env
DB_DATABASE=db-clearance-system
APP_URL=http://localhost:8000
```

**CMS** (`clearance-managment-system/.env`):
```env
DB_DATABASE=db-clearance-system
APP_URL=http://localhost:8001
```

### Step 2: Start Both Applications

Open two terminal windows:

**Terminal 1 — GMAMS:**
```bash
cd "path/to/GoodMoralApplication"
php artisan serve
```

**Terminal 2 — CMS:**
```bash
cd "path/to/GoodMoralApplication/clearance-managment-system"
php artisan serve --port=8001
```

### Step 3: Access the Systems

- GMAMS: `http://localhost:8000`
- CMS: `http://localhost:8001`

---

## Navigation Integration

A "My Clearance" link in the GMAMS student dashboard opens the CMS with auto-login:

```blade
<a href="{{ route('clearance.auto-login') }}"
   class="nav-link"
   target="_blank"
   title="Opens clearance system in new window">
    <span>My Clearance</span>
</a>
```

The auto-login route generates a signed, time-limited token and redirects the student directly into the CMS dashboard.

---

## Auto-Login Flow

```
Student clicks "My Clearance" in GMAMS
    │
    ▼
GMAMS generates encrypted token (Laravel signed URL)
    │
    ▼
Redirect to CMS: /auto-login?token=...
    │
    ▼
CMS validates token → logs student in → CMS dashboard
```

---

## Notes

- The auto-login token is short-lived (expires in 60 seconds).
- Tokens are signed using the application key — they cannot be forged.
- If the student is already logged in to CMS in the same browser, the auto-login is skipped.
