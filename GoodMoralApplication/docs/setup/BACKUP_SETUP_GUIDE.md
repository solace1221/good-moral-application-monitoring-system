# Database Backup Setup Guide

## Overview

This guide covers setting up automated database backups for the `db-clearance-system` MySQL database used by GMAMS.

---

## Prerequisites

- MySQL client tools installed (`mysqldump` available in PATH)
- PowerShell 5.1+ (Windows) or Bash (Linux/macOS)
- Write access to the backup destination directory

---

## Manual Backup

### Windows (PowerShell)

```powershell
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
mysqldump -u root -p db-clearance-system > "backup_$timestamp.sql"
```

### Linux/macOS (Bash)

```bash
mysqldump -u root -p db-clearance-system > backup_$(date +%Y%m%d_%H%M%S).sql
```

---

## Automated Backup (Windows Task Scheduler)

### Step 1: Configure the PowerShell Script

Edit `backup-database.ps1` in the project root. Set the following variables:

```powershell
$DbHost     = "localhost"
$DbPort     = "3306"
$DbName     = "db-clearance-system"
$DbUser     = "root"
$DbPassword = "your_password"
$BackupDir  = "C:\Backups\GoodMoral"
$KeepDays   = 7   # Delete backups older than this
```

### Step 2: Create the Backup Directory

```powershell
New-Item -ItemType Directory -Path "C:\Backups\GoodMoral" -Force
```

### Step 3: Schedule the Task

```powershell
$action  = New-ScheduledTaskAction -Execute "powershell.exe" `
           -Argument "-File C:\path\to\GoodMoralApplication\backup-database.ps1"
$trigger = New-ScheduledTaskTrigger -Daily -At "02:00AM"
Register-ScheduledTask -TaskName "GMAMS-DB-Backup" -Action $action -Trigger $trigger -RunLevel Highest
```

---

## Using the Batch Script (Alternative)

The project also includes `backup-database.bat` for environments where PowerShell is restricted:

```batch
backup-database.bat
```

This produces a file named `backup_YYYYMMDD_HHMMSS.sql` in the project root.

---

## Restore Procedure

```bash
mysql -u root -p db-clearance-system < backup_YYYYMMDD_HHMMSS.sql
```

---

## Backup Retention

- Default retention: 7 days (configurable in `backup-database.ps1`)
- Backups older than the retention period are deleted automatically by the script
