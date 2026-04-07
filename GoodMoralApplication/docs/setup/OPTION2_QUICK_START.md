# Option 2: Modular Integration — Quick Start

## Status: Implemented

Both systems run independently and share the `db-clearance-system` database for authentication.

---

## Starting Both Systems

### Step 1: Open Two Terminals

### Step 2: Start GMAMS (Terminal 1)

```bash
cd "path/to/GoodMoralApplication"
php artisan serve
```

Result: `http://127.0.0.1:8000`

### Step 3: Start CMS (Terminal 2)

```bash
cd "path/to/GoodMoralApplication/clearance-managment-system"
php artisan serve --port=8001
```

Result: `http://127.0.0.1:8001`

---

## Access

| System | URL | Notes |
|--------|-----|-------|
| Good Moral Application | http://localhost:8000 | Main application |
| Clearance Management System | http://localhost:8001 | Via "My Clearance" link or direct |

Login credentials are the same for both systems (shared database).

---

## Auto-Login Feature

Clicking **My Clearance** in the GMAMS navigation bar automatically logs you into CMS — no second login required. The auto-login uses a secure, time-limited encrypted token.

---

## What Was Implemented

- Shared `db-clearance-system` database for both applications
- "My Clearance" navigation link in GMAMS student dashboard
- Auto-login endpoint in CMS (`/auto-login?token=...`)
- Token generation in GMAMS using Laravel signed URLs
- Token validation in CMS before creating the authenticated session

For full implementation details, see [OPTION2_MODULAR_INTEGRATION.md](OPTION2_MODULAR_INTEGRATION.md).
