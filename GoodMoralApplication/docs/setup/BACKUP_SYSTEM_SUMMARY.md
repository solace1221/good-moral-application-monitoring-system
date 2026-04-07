# Backup System Summary

## Overview

GMAMS includes a database backup system for the shared `db-clearance-system` MySQL database. Two backup scripts are provided: one for PowerShell and one as a Windows batch file.

---

## Backup Scripts

| File | Description |
|------|-------------|
| `backup-database.ps1` | PowerShell script — configurable retention, timestamped filenames |
| `backup-database.bat` | Windows batch file — simple backup with no dependencies |

---

## What Gets Backed Up

The backup covers the full `db-clearance-system` database, including:

- `users` — authentication accounts
- `student_registrations` — student profile data
- `role_account` — role-based user records
- `student_violations` — violation records
- `good_moral_applications` — application records
- `good_moral_certificates` — generated certificates
- All CMS tables (clearances, departments, academic years, etc.)

---

## Backup File Format

```
backup_YYYYMMDD_HHMMSS.sql
```

Example: `backup_20250210_020000.sql`

---

## Retention Policy

Default: 7 days. Backups older than the configured retention period are deleted automatically by `backup-database.ps1`.

---

## Recommendations

- Run backups before any deployment or database migration.
- Store backups on a separate drive or network location, not only on the application server.
- Test the restore procedure at least once to confirm backup integrity.

For full setup instructions, see [BACKUP_SETUP_GUIDE.md](BACKUP_SETUP_GUIDE.md).
