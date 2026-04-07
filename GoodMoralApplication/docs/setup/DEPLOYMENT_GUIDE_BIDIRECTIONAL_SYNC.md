# Bidirectional Synchronization — Deployment Guide

## Overview

This guide provides step-by-step instructions for deploying the GMAMS ↔ CMS bidirectional synchronization. Follow this guide after reviewing the [Pre-Deployment Checklist](../reference/IMPLEMENTATION_CHECKLIST.md).

---

## Pre-Deployment Checklist

- [ ] Back up the `db-clearance-system` database:
  ```bash
  mysqldump -u root -p db-clearance-system > backup_$(date +%Y%m%d_%H%M%S).sql
  ```
- [ ] Back up application files:
  ```bash
  cp -r app app_backup_$(date +%Y%m%d)
  cp -r clearance-managment-system/app clearance_app_backup_$(date +%Y%m%d)
  ```
- [ ] Verify both applications start without errors
- [ ] Check database connection:
  ```bash
  php artisan tinker
  >>> DB::connection()->getPdo();
  ```
- [ ] Review logs: `tail -100 storage/logs/laravel.log`

---

## Deployment Steps

### Step 1: Deploy `GoodMoralSyncService`

**File**: `app/Services/GoodMoralSyncService.php`

Verify the file exists and has no syntax errors:

```bash
php -l app/Services/GoodMoralSyncService.php
```

Expected: `No syntax errors detected`

---

### Step 2: Update CMS Registration Controller

**File**: `clearance-managment-system/app/Http/Controllers/Auth/RegisteredUserController.php`

Verify the sync call is present:

```bash
grep -n "GoodMoralSyncService\|syncToGoodMoral" \
  clearance-managment-system/app/Http/Controllers/Auth/RegisteredUserController.php
```

---

### Step 3: Batch Sync Existing CMS Users

Run the batch sync to populate GMAMS with all existing CMS users:

```bash
php batch_sync_cms_to_gmams.php
```

Review the output for any errors before proceeding.

---

### Step 4: Clear Application Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

### Step 5: Run Automated Tests

```bash
php test_bidirectional_sync.php
```

Expected output:
```
ALL TESTS PASSED!
  GMAMS → CMS: OK
  CMS → GMAMS: OK
```

---

## Post-Deployment Validation

1. Register a new test student in GMAMS → confirm the record appears in CMS.
2. Register a new test student in CMS → confirm the record appears in GMAMS.
3. Update profile in GMAMS (name, email, password) → confirm changes appear in CMS.
4. Check logs: no sync errors in `storage/logs/laravel.log`.

---

## Rollback

If the deployment causes issues:

```bash
# Restore database
mysql -u root -p db-clearance-system < backup_YYYYMMDD_HHMMSS.sql

# Restore service file
cp app_backup_YYYYMMDD/Services/GoodMoralSyncService.php app/Services/
```

---

## Related Files

| File | Description |
|------|-------------|
| `app/Services/GoodMoralSyncService.php` | CMS → GMAMS sync service |
| `app/Services/ClearanceSyncService.php` | GMAMS → CMS sync service |
| `batch_sync_cms_to_gmams.php` | Batch sync utility |
| `test_bidirectional_sync.php` | Automated test suite |
