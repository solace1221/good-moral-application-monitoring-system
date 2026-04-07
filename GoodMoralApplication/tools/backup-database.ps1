# SPUP Good Moral Application System - Database Backup Tool (PowerShell)
# This script provides an interactive interface for database backup operations

param(
    [string]$Action = "",
    [switch]$Compress,
    [switch]$Latest,
    [string]$File = "",
    [int]$Days = 30,
    [switch]$Force
)

# Colors for output
$ErrorColor = "Red"
$WarningColor = "Yellow"
$SuccessColor = "Green"
$InfoColor = "Cyan"

function Write-Header {
    Write-Host "========================================" -ForegroundColor $InfoColor
    Write-Host "   SPUP Good Moral Application System" -ForegroundColor $InfoColor
    Write-Host "        Database Backup Tool" -ForegroundColor $InfoColor
    Write-Host "========================================" -ForegroundColor $InfoColor
    Write-Host ""
}

function Test-Prerequisites {
    # Check if PHP is available
    try {
        $phpVersion = php --version 2>$null
        if ($LASTEXITCODE -ne 0) {
            throw "PHP not found"
        }
    }
    catch {
        Write-Host "ERROR: PHP is not installed or not in PATH" -ForegroundColor $ErrorColor
        Write-Host "Please install PHP or add it to your system PATH" -ForegroundColor $ErrorColor
        exit 1
    }

    # Check if we're in the correct directory
    if (-not (Test-Path "artisan")) {
        Write-Host "ERROR: artisan file not found" -ForegroundColor $ErrorColor
        Write-Host "Please run this script from the Laravel project root directory" -ForegroundColor $ErrorColor
        exit 1
    }
}

function Show-Menu {
    Write-Host ""
    Write-Host "Choose an option:" -ForegroundColor $InfoColor
    Write-Host "1. Create new backup"
    Write-Host "2. Create compressed backup"
    Write-Host "3. List existing backups"
    Write-Host "4. Restore from backup"
    Write-Host "5. Manage backups (clean/delete)"
    Write-Host "6. Exit"
    Write-Host ""
}

function Invoke-Backup {
    param([switch]$Compress)
    
    Write-Host ""
    if ($Compress) {
        Write-Host "Creating compressed database backup..." -ForegroundColor $InfoColor
        $result = php artisan db:backup --compress
    } else {
        Write-Host "Creating database backup..." -ForegroundColor $InfoColor
        $result = php artisan db:backup
    }
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "✅ Backup completed successfully!" -ForegroundColor $SuccessColor
    } else {
        Write-Host ""
        Write-Host "❌ Backup failed!" -ForegroundColor $ErrorColor
    }
}

function Show-Backups {
    Write-Host ""
    Write-Host "Listing available backups..." -ForegroundColor $InfoColor
    php artisan db:restore --list
}

function Invoke-Restore {
    Write-Host ""
    Write-Host "⚠️  WARNING: This will replace your current database!" -ForegroundColor $WarningColor
    Write-Host "⚠️  All current data will be lost!" -ForegroundColor $WarningColor
    Write-Host ""
    
    if (-not $Force) {
        $confirm = Read-Host "Are you sure you want to continue? (y/N)"
        if ($confirm -ne "y" -and $confirm -ne "Y") {
            Write-Host "Restore cancelled." -ForegroundColor $InfoColor
            return
        }
    }

    Write-Host ""
    Write-Host "Choose restore option:" -ForegroundColor $InfoColor
    Write-Host "1. Restore from latest backup"
    Write-Host "2. Restore from specific backup file"
    Write-Host "3. Cancel"
    Write-Host ""
    
    $choice = Read-Host "Enter your choice (1-3)"
    
    switch ($choice) {
        "1" {
            Write-Host ""
            Write-Host "Restoring from latest backup..." -ForegroundColor $InfoColor
            php artisan db:restore --latest --force
        }
        "2" {
            Write-Host ""
            $backupFile = Read-Host "Enter backup filename"
            Write-Host "Restoring from $backupFile..." -ForegroundColor $InfoColor
            php artisan db:restore $backupFile --force
        }
        "3" {
            Write-Host "Restore cancelled." -ForegroundColor $InfoColor
            return
        }
        default {
            Write-Host "Invalid choice." -ForegroundColor $ErrorColor
            return
        }
    }
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "✅ Database restored successfully!" -ForegroundColor $SuccessColor
    } else {
        Write-Host ""
        Write-Host "❌ Restore failed!" -ForegroundColor $ErrorColor
    }
}

function Invoke-BackupManagement {
    Write-Host ""
    Write-Host "Backup Management Options:" -ForegroundColor $InfoColor
    Write-Host "1. List all backups with details"
    Write-Host "2. Clean old backups (older than 30 days)"
    Write-Host "3. Clean old backups (custom days)"
    Write-Host "4. Delete specific backup"
    Write-Host "5. Back to main menu"
    Write-Host ""
    
    $choice = Read-Host "Enter your choice (1-5)"
    
    switch ($choice) {
        "1" {
            Write-Host ""
            php artisan backup:manage list
        }
        "2" {
            Write-Host ""
            Write-Host "Cleaning backups older than 30 days..." -ForegroundColor $InfoColor
            php artisan backup:manage clean --days=30
        }
        "3" {
            Write-Host ""
            $days = Read-Host "Enter number of days to keep"
            Write-Host "Cleaning backups older than $days days..." -ForegroundColor $InfoColor
            php artisan backup:manage clean --days=$days
        }
        "4" {
            Write-Host ""
            $deleteFile = Read-Host "Enter backup filename to delete"
            php artisan backup:manage delete --file=$deleteFile
        }
        "5" {
            return
        }
        default {
            Write-Host "Invalid choice." -ForegroundColor $ErrorColor
        }
    }
}

# Main execution
Write-Header
Test-Prerequisites

# Handle command line parameters
if ($Action -ne "") {
    switch ($Action.ToLower()) {
        "backup" {
            Invoke-Backup -Compress:$Compress
            exit
        }
        "list" {
            Show-Backups
            exit
        }
        "restore" {
            if ($Latest) {
                php artisan db:restore --latest --force
            } elseif ($File -ne "") {
                php artisan db:restore $File --force
            } else {
                Write-Host "Please specify --Latest or --File parameter for restore" -ForegroundColor $ErrorColor
            }
            exit
        }
        "clean" {
            php artisan backup:manage clean --days=$Days
            exit
        }
        default {
            Write-Host "Unknown action: $Action" -ForegroundColor $ErrorColor
            Write-Host "Available actions: backup, list, restore, clean" -ForegroundColor $InfoColor
            exit 1
        }
    }
}

# Interactive mode
do {
    Show-Menu
    $choice = Read-Host "Enter your choice (1-6)"
    
    switch ($choice) {
        "1" { Invoke-Backup }
        "2" { Invoke-Backup -Compress }
        "3" { Show-Backups }
        "4" { Invoke-Restore }
        "5" { Invoke-BackupManagement }
        "6" { 
            Write-Host ""
            Write-Host "Thank you for using the Database Backup Tool!" -ForegroundColor $SuccessColor
            Write-Host "Goodbye!" -ForegroundColor $InfoColor
            exit 0
        }
        default {
            Write-Host "Invalid choice. Please try again." -ForegroundColor $ErrorColor
        }
    }
    
    if ($choice -ne "6") {
        Write-Host ""
        Read-Host "Press Enter to continue..."
    }
} while ($choice -ne "6")
