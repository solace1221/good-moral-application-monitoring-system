# Bidirectional Sync — Deployment Checklist

## Overview

Use this checklist before and after deploying the GMAMS ↔ CMS bidirectional synchronization.

---

## Pre-Deployment

- [ ] Back up `db-clearance-system` database
- [ ] Verify `app/Services/GoodMoralSyncService.php` exists
- [ ] Verify `app/Services/ClearanceSyncService.php` exists
- [ ] Confirm CMS `RegisteredUserController` includes the sync call
- [ ] Confirm both applications start without errors
- [ ] Review `storage/logs/laravel.log` for pre-existing errors
- [ ] Test database connection: `php artisan tinker` → `DB::connection()->getPdo()`

---

## Deployment Steps

- [ ] **Step 1** — Deploy `GoodMoralSyncService.php` to GMAMS
- [ ] **Step 2** — Deploy updated `RegisteredUserController.php` to CMS
- [ ] **Step 3** — Run batch sync for existing CMS users: `php batch_sync_cms_to_gmams.php`
- [ ] **Step 4** — Clear caches on both applications:
  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
  ```
- [ ] **Step 5** — Run automated tests: `php test_bidirectional_sync.php`

---

## Post-Deployment Validation

- [ ] Register test user in GMAMS → verify appears in CMS
- [ ] Register test user in CMS → verify appears in GMAMS
- [ ] Update profile in GMAMS → verify changes sync to CMS
- [ ] Verify logs show no sync errors: `tail -50 storage/logs/laravel.log`
- [ ] Confirm email notifications are sent and received

---

## Rollback Procedure

If deployment fails:

```bash
# Restore database
mysql -u root -p db-clearance-system < backup_YYYYMMDD_HHMMSS.sql

# Restore application files from backup
cp -r app_backup_YYYYMMDD/Services/GoodMoralSyncService.php app/Services/
```
