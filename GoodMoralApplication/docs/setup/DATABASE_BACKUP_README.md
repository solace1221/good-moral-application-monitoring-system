# Database Backup — Command Reference

## Quick Commands

### Create a Full Backup

```bash
mysqldump -u root -p db-clearance-system > backup_$(date +%Y%m%d_%H%M%S).sql
```

**Windows PowerShell:**
```powershell
$ts = Get-Date -Format "yyyyMMdd_HHmmss"
mysqldump -u root -p db-clearance-system > "backup_$ts.sql"
```

---

### Restore from Backup

```bash
mysql -u root -p db-clearance-system < backup_YYYYMMDD_HHMMSS.sql
```

---

### Backup a Single Table

```bash
mysqldump -u root -p db-clearance-system users > users_backup.sql
```

---

### List Available Backups

```bash
ls -lh backup_*.sql
```

**Windows:**
```powershell
Get-ChildItem backup_*.sql | Select-Object Name, Length, LastWriteTime
```

---

### Run Project Backup Scripts

```powershell
# PowerShell (Windows)
.\backup-database.ps1

# Batch (Windows)
backup-database.bat
```

---

## Notes

- Always back up before running `php artisan migrate` or deploying changes.
- The `db-clearance-system` database is shared between GMAMS and CMS — a single backup covers both systems.
- For automated scheduling, see [BACKUP_SETUP_GUIDE.md](BACKUP_SETUP_GUIDE.md).
