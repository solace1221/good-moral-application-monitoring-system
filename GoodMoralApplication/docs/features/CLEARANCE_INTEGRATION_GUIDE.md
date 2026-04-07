# Clearance Integration Guide

## Overview

This guide describes how GMAMS integrates with the Clearance Management System (CMS) and provides steps for testing and maintaining the integration.

---

## Integration Points

| Feature | Description |
|---------|-------------|
| Shared database | `db-clearance-system` — both apps use the same MySQL database |
| Account sync (register) | New GMAMS student → auto-created in CMS |
| Account sync (CMS → GMAMS) | New CMS student → auto-created in GMAMS |
| Profile sync | Profile update in GMAMS → reflected in CMS |
| Auto-login | "My Clearance" link from GMAMS logs student into CMS automatically |

---

## Integration Setup Steps

### Step 1: Verify Database Connection

Both applications must use the same `db-clearance-system` database. Check each `.env`:

```env
DB_DATABASE=db-clearance-system
DB_HOST=127.0.0.1
DB_PORT=3306
```

### Step 2: Verify Sync Services

```bash
php -l app/Services/ClearanceSyncService.php
php -l app/Services/GoodMoralSyncService.php
```

Both should return: `No syntax errors detected`

### Step 3: Run Tests

```bash
php test_bidirectional_sync.php
```

### Step 4: Batch Sync Existing Users (if needed)

```bash
php batch_sync_cms_to_gmams.php
```

---

## Auto-Login Flow

```
1. Student logs in to GMAMS
2. Student clicks "My Clearance" in navigation
3. GMAMS generates a signed, time-limited token (60 seconds)
4. Browser redirected to CMS: /auto-login?token=<signed_token>
5. CMS validates token → creates session → student lands on CMS dashboard
```

The token is signed using the application key and cannot be forged or reused after expiry.

---

## Troubleshooting

| Issue | Steps |
|-------|-------|
| Student not found in CMS after GMAMS registration | Check `storage/logs/laravel.log` for sync errors; re-run `php batch_sync_cms_to_gmams.php` |
| Auto-login fails | Verify `APP_KEY` is the same in both `.env` files (or tokens won't validate) |
| Clearance records missing | Log in to CMS admin and trigger manual clearance generation for the academic year |
