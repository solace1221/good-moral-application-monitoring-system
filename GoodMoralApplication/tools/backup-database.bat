@echo off
echo ========================================
echo    SPUP Good Moral Application System
echo         Database Backup Tool
echo ========================================
echo.

REM Check if PHP is available
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP is not installed or not in PATH
    echo Please install PHP or add it to your system PATH
    pause
    exit /b 1
)

REM Check if we're in the correct directory
if not exist "artisan" (
    echo ERROR: artisan file not found
    echo Please run this script from the Laravel project root directory
    pause
    exit /b 1
)

:menu
echo.
echo Choose an option:
echo 1. Create new backup (PHP method - always works)
echo 2. Create compressed backup (PHP method)
echo 3. Create MySQL backup (requires mysqldump)
echo 4. Create compressed MySQL backup
echo 5. List existing backups
echo 6. Restore from backup
echo 7. Manage backups (clean/delete)
echo 8. Exit
echo.
set /p choice="Enter your choice (1-8): "

if "%choice%"=="1" goto backup_php
if "%choice%"=="2" goto backup_php_compressed
if "%choice%"=="3" goto backup_mysql
if "%choice%"=="4" goto backup_mysql_compressed
if "%choice%"=="5" goto list_backups
if "%choice%"=="6" goto restore
if "%choice%"=="7" goto manage
if "%choice%"=="8" goto exit
echo Invalid choice. Please try again.
goto menu

:backup_php
echo.
echo Creating PHP database backup (no mysqldump required)...
php artisan db:backup-php
if %errorlevel% equ 0 (
    echo.
    echo ✅ PHP Backup completed successfully!
) else (
    echo.
    echo ❌ PHP Backup failed!
)
pause
goto menu

:backup_php_compressed
echo.
echo Creating compressed PHP database backup...
php artisan db:backup-php --compress
if %errorlevel% equ 0 (
    echo.
    echo ✅ Compressed PHP backup completed successfully!
) else (
    echo.
    echo ❌ PHP Backup failed!
)
pause
goto menu

:backup_mysql
echo.
echo Creating MySQL database backup (requires mysqldump)...
php artisan db:backup
if %errorlevel% equ 0 (
    echo.
    echo ✅ MySQL Backup completed successfully!
) else (
    echo.
    echo ❌ MySQL Backup failed! Try PHP backup instead (option 1 or 2)
)
pause
goto menu

:backup_mysql_compressed
echo.
echo Creating compressed MySQL database backup...
php artisan db:backup --compress
if %errorlevel% equ 0 (
    echo.
    echo ✅ Compressed MySQL backup completed successfully!
) else (
    echo.
    echo ❌ MySQL Backup failed! Try PHP backup instead (option 1 or 2)
)
pause
goto menu

:list_backups
echo.
echo Listing available backups...
php artisan db:restore --list
pause
goto menu

:restore
echo.
echo ⚠️  WARNING: This will replace your current database!
echo ⚠️  All current data will be lost!
echo.
set /p confirm="Are you sure you want to continue? (y/N): "
if /i not "%confirm%"=="y" (
    echo Restore cancelled.
    pause
    goto menu
)

echo.
echo Choose restore option:
echo 1. Restore from latest backup
echo 2. Restore from specific backup file
echo 3. Cancel
echo.
set /p restore_choice="Enter your choice (1-3): "

if "%restore_choice%"=="1" (
    echo.
    echo Restoring from latest backup...
    php artisan db:restore --latest --force
) else if "%restore_choice%"=="2" (
    echo.
    set /p backup_file="Enter backup filename: "
    echo Restoring from %backup_file%...
    php artisan db:restore "%backup_file%" --force
) else if "%restore_choice%"=="3" (
    echo Restore cancelled.
    pause
    goto menu
) else (
    echo Invalid choice.
    pause
    goto menu
)

if %errorlevel% equ 0 (
    echo.
    echo ✅ Database restored successfully!
) else (
    echo.
    echo ❌ Restore failed!
)
pause
goto menu

:manage
echo.
echo Backup Management Options:
echo 1. List all backups with details
echo 2. Clean old backups (older than 30 days)
echo 3. Clean old backups (custom days)
echo 4. Delete specific backup
echo 5. Back to main menu
echo.
set /p manage_choice="Enter your choice (1-5): "

if "%manage_choice%"=="1" (
    echo.
    php artisan backup:manage list
) else if "%manage_choice%"=="2" (
    echo.
    echo Cleaning backups older than 30 days...
    php artisan backup:manage clean --days=30
) else if "%manage_choice%"=="3" (
    echo.
    set /p days="Enter number of days to keep: "
    echo Cleaning backups older than %days% days...
    php artisan backup:manage clean --days=%days%
) else if "%manage_choice%"=="4" (
    echo.
    set /p delete_file="Enter backup filename to delete: "
    php artisan backup:manage delete --file="%delete_file%"
) else if "%manage_choice%"=="5" (
    goto menu
) else (
    echo Invalid choice.
)
pause
goto menu

:exit
echo.
echo Thank you for using the Database Backup Tool!
echo Goodbye!
pause
exit /b 0
