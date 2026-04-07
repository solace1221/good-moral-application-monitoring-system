<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore 
                            {backup? : Backup file path or filename}
                            {--list : List available backups}
                            {--latest : Restore from latest backup}
                            {--force : Force restore without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database from backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('list')) {
            return $this->listBackups();
        }

        $backupFile = $this->getBackupFile();
        
        if (!$backupFile) {
            $this->error('No backup file specified or found.');
            return 1;
        }

        if (!file_exists($backupFile)) {
            $this->error("Backup file not found: {$backupFile}");
            return 1;
        }

        // Show warning
        $this->warn('⚠️  WARNING: This will replace your current database!');
        $this->warn('⚠️  All current data will be lost!');
        
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to continue?')) {
                $this->info('Restore cancelled.');
                return 0;
            }
        }

        return $this->restoreDatabase($backupFile);
    }

    /**
     * List available backups
     */
    private function listBackups()
    {
        $this->info('Available Database Backups:');
        
        // Check for backups in storage directory
        $backupDir = storage_path('app/backups');
        $backups = [];
        
        if (is_dir($backupDir)) {
            $files = glob($backupDir . '/backup_*.sql*');
            foreach ($files as $file) {
                $backups[] = [
                    'File' => basename($file),
                    'Size' => $this->formatBytes(filesize($file)),
                    'Date' => date('Y-m-d H:i:s', filemtime($file)),
                    'Path' => $file
                ];
            }
        }

        // Also check database records
        try {
            $dbBackups = DB::table('database_backups')
                ->orderBy('created_at', 'desc')
                ->get();
            
            foreach ($dbBackups as $backup) {
                if (file_exists($backup->filepath)) {
                    $found = false;
                    foreach ($backups as $existing) {
                        if ($existing['File'] === $backup->filename) {
                            $found = true;
                            break;
                        }
                    }
                    
                    if (!$found) {
                        $backups[] = [
                            'File' => $backup->filename,
                            'Size' => $backup->file_size,
                            'Date' => $backup->created_at,
                            'Path' => $backup->filepath
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Database table might not exist
        }

        if (empty($backups)) {
            $this->warn('No backups found.');
            $this->info('Create a backup first using: php artisan db:backup');
            return 0;
        }

        // Sort by date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['Date']) - strtotime($a['Date']);
        });

        $this->table(['File', 'Size', 'Date'], array_map(function($backup) {
            return [$backup['File'], $backup['Size'], $backup['Date']];
        }, $backups));

        $this->info("\nTo restore a backup, use:");
        $this->line("php artisan db:restore <filename>");
        $this->line("php artisan db:restore --latest");

        return 0;
    }

    /**
     * Get the backup file to restore
     */
    private function getBackupFile()
    {
        if ($this->option('latest')) {
            return $this->getLatestBackup();
        }

        $backup = $this->argument('backup');
        if (!$backup) {
            return null;
        }

        // If it's a full path, use it
        if (file_exists($backup)) {
            return $backup;
        }

        // Check in backup directory
        $backupDir = storage_path('app/backups');
        $fullPath = $backupDir . '/' . $backup;
        
        if (file_exists($fullPath)) {
            return $fullPath;
        }

        return null;
    }

    /**
     * Get the latest backup file
     */
    private function getLatestBackup()
    {
        $backupDir = storage_path('app/backups');
        $files = glob($backupDir . '/backup_*.sql*');
        
        if (empty($files)) {
            return null;
        }

        // Sort by modification time (newest first)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        return $files[0];
    }

    /**
     * Restore database from backup file
     */
    private function restoreDatabase($backupFile)
    {
        $this->info("Restoring database from: " . basename($backupFile));

        try {
            $config = config('database.connections.' . config('database.default'));

            // Handle compressed files
            if (pathinfo($backupFile, PATHINFO_EXTENSION) === 'gz') {
                $this->info('Decompressing backup file...');
                $tempFile = storage_path('app/temp_restore.sql');
                exec("gunzip -c \"{$backupFile}\" > \"{$tempFile}\"", $output, $returnCode);
                
                if ($returnCode !== 0) {
                    $this->error('Failed to decompress backup file.');
                    return 1;
                }
                
                $backupFile = $tempFile;
            }

            // Build mysql restore command
            $command = 'mysql';
            $command .= " --host={$config['host']}";
            $command .= " --port={$config['port']}";
            $command .= " --user={$config['username']}";
            
            if (!empty($config['password'])) {
                $command .= " --password=\"{$config['password']}\"";
            }

            $command .= " {$config['database']} < \"{$backupFile}\"";

            $this->info('Executing restore...');
            exec($command, $output, $returnCode);

            // Clean up temp file if created
            if (isset($tempFile) && file_exists($tempFile)) {
                unlink($tempFile);
            }

            if ($returnCode !== 0) {
                $this->error('Database restore failed!');
                $this->error('Output: ' . implode("\n", $output));
                return 1;
            }

            $this->info('✅ Database restored successfully!');
            $this->info('Backup file: ' . basename($backupFile));
            $this->info('Restored at: ' . Carbon::now()->format('Y-m-d H:i:s'));

            return 0;

        } catch (\Exception $e) {
            $this->error('Restore failed with error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
