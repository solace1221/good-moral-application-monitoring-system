<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:manage 
                            {action : Action to perform (list|clean|delete)}
                            {--days=30 : Days to keep backups (for clean action)}
                            {--file= : Specific file to delete}
                            {--force : Force action without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage database backups (list, clean old backups, delete specific backup)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                return $this->listBackups();
            case 'clean':
                return $this->cleanOldBackups();
            case 'delete':
                return $this->deleteBackup();
            default:
                $this->error("Unknown action: {$action}");
                $this->info("Available actions: list, clean, delete");
                return 1;
        }
    }

    /**
     * List all backups with detailed information
     */
    private function listBackups()
    {
        $this->info('📁 Database Backup Management');
        $this->line('');

        $backupDir = storage_path('app/backups');
        $backups = [];
        $totalSize = 0;

        if (is_dir($backupDir)) {
            $files = glob($backupDir . '/backup_*.sql*');
            foreach ($files as $file) {
                $size = filesize($file);
                $totalSize += $size;
                
                $backups[] = [
                    'File' => basename($file),
                    'Size' => $this->formatBytes($size),
                    'Date' => date('Y-m-d H:i:s', filemtime($file)),
                    'Age' => $this->getFileAge($file),
                    'Path' => $file
                ];
            }
        }

        if (empty($backups)) {
            $this->warn('No backups found.');
            return 0;
        }

        // Sort by date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['Date']) - strtotime($a['Date']);
        });

        $this->table(['File', 'Size', 'Date', 'Age'], array_map(function($backup) {
            return [$backup['File'], $backup['Size'], $backup['Date'], $backup['Age']];
        }, $backups));

        $this->info("📊 Summary:");
        $this->line("Total backups: " . count($backups));
        $this->line("Total size: " . $this->formatBytes($totalSize));
        $this->line("Backup directory: {$backupDir}");

        return 0;
    }

    /**
     * Clean old backups
     */
    private function cleanOldBackups()
    {
        $days = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("🧹 Cleaning backups older than {$days} days (before {$cutoffDate->format('Y-m-d H:i:s')})");

        $backupDir = storage_path('app/backups');
        $files = glob($backupDir . '/backup_*.sql*');
        $oldFiles = [];
        $totalSize = 0;

        foreach ($files as $file) {
            $fileDate = Carbon::createFromTimestamp(filemtime($file));
            if ($fileDate->lt($cutoffDate)) {
                $size = filesize($file);
                $totalSize += $size;
                $oldFiles[] = [
                    'file' => $file,
                    'name' => basename($file),
                    'date' => $fileDate->format('Y-m-d H:i:s'),
                    'size' => $this->formatBytes($size)
                ];
            }
        }

        if (empty($oldFiles)) {
            $this->info("✅ No old backups found to clean.");
            return 0;
        }

        $this->warn("Found " . count($oldFiles) . " old backup(s) to delete:");
        $this->table(['File', 'Date', 'Size'], array_map(function($file) {
            return [$file['name'], $file['date'], $file['size']];
        }, $oldFiles));

        $this->line("Total space to be freed: " . $this->formatBytes($totalSize));

        if (!$this->option('force')) {
            if (!$this->confirm('Do you want to delete these backups?')) {
                $this->info('Cleanup cancelled.');
                return 0;
            }
        }

        $deleted = 0;
        foreach ($oldFiles as $fileInfo) {
            if (unlink($fileInfo['file'])) {
                $deleted++;
                $this->line("✅ Deleted: " . $fileInfo['name']);
                
                // Remove from database tracking if exists
                try {
                    DB::table('database_backups')
                        ->where('filename', $fileInfo['name'])
                        ->delete();
                } catch (\Exception $e) {
                    // Table might not exist
                }
            } else {
                $this->error("❌ Failed to delete: " . $fileInfo['name']);
            }
        }

        $this->info("🎉 Cleanup completed! Deleted {$deleted} backup(s), freed " . $this->formatBytes($totalSize));
        return 0;
    }

    /**
     * Delete a specific backup
     */
    private function deleteBackup()
    {
        $filename = $this->option('file');
        
        if (!$filename) {
            $this->error('Please specify a file to delete using --file option');
            return 1;
        }

        $backupDir = storage_path('app/backups');
        $filepath = $backupDir . '/' . $filename;

        if (!file_exists($filepath)) {
            $this->error("Backup file not found: {$filename}");
            return 1;
        }

        $size = filesize($filepath);
        $date = date('Y-m-d H:i:s', filemtime($filepath));

        $this->warn("⚠️  You are about to delete:");
        $this->table(['Property', 'Value'], [
            ['File', $filename],
            ['Size', $this->formatBytes($size)],
            ['Date', $date],
            ['Path', $filepath]
        ]);

        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to delete this backup?')) {
                $this->info('Deletion cancelled.');
                return 0;
            }
        }

        if (unlink($filepath)) {
            $this->info("✅ Backup deleted successfully: {$filename}");
            
            // Remove from database tracking if exists
            try {
                DB::table('database_backups')
                    ->where('filename', $filename)
                    ->delete();
                $this->info("✅ Removed from backup tracking database.");
            } catch (\Exception $e) {
                // Table might not exist
            }
            
            return 0;
        } else {
            $this->error("❌ Failed to delete backup: {$filename}");
            return 1;
        }
    }

    /**
     * Get file age in human readable format
     */
    private function getFileAge($file)
    {
        $fileDate = Carbon::createFromTimestamp(filemtime($file));
        return $fileDate->diffForHumans();
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
