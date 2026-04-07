# Account Sync — Complete

## Status: Implemented and Verified

The integrated account system is fully operational. A single registration in either system creates accounts in both GMAMS and CMS.

---

## What Works

- Student registers in GMAMS → account automatically created in CMS
- Student registers in CMS → account automatically created in GMAMS
- Same email and password work for both systems
- Profile updates (name, email, password) in GMAMS sync to CMS automatically

---

## Architecture

Both systems share the `db-clearance-system` MySQL database. The sync services write directly to the shared tables using separate database connections:

```
GMAMS tables:          CMS tables:
  users                  clearance_users (alias: users in CMS)
  student_registrations  students
  role_account           clearances
```

---

## Related Files

| File | Description |
|------|-------------|
| `app/Services/ClearanceSyncService.php` | GMAMS → CMS sync |
| `app/Services/GoodMoralSyncService.php` | CMS → GMAMS sync |
| `app/Http/Controllers/ApplicationController.php` | Profile update sync |

For technical details, see [ACCOUNT_SYNC_IMPLEMENTATION.md](ACCOUNT_SYNC_IMPLEMENTATION.md).  
For quick start instructions, see [QUICK_START_ACCOUNT_SYNC.md](QUICK_START_ACCOUNT_SYNC.md).
