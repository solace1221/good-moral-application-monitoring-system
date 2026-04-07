<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup 
                            {--path= : Custom backup path}
                            {--compress : Compress the backup file}
                            {--tables= : Specific tables to backup (comma separated)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        try {
            // Get database configuration
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");

            if ($connection !== 'mysql') {
                $this->error('This backup command currently supports MySQL only.');
                return 1;
            }

            // Create backup directory if it doesn't exist
            $backupDir = $this->option('path') ?: storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Generate backup filename
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$config['database']}_{$timestamp}.sql";
            $filepath = $backupDir . '/' . $filename;

            // Build mysqldump command
            $command = $this->buildMysqlDumpCommand($config, $filepath);

            $this->info("Executing backup command...");
            $this->line("Backup file: {$filepath}");

            // Execute the backup command
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $this->error('Backup failed!');
                $this->error('Command: ' . $command);
                $this->error('Output: ' . implode("\n", $output));
                return 1;
            }

            // Compress if requested
            if ($this->option('compress')) {
                $this->info('Compressing backup file...');
                $compressedFile = $filepath . '.gz';
                exec("gzip {$filepath}", $output, $returnCode);
                
                if ($returnCode === 0) {
                    $filepath = $compressedFile;
                    $this->info("Backup compressed successfully!");
                } else {
                    $this->warn("Compression failed, keeping uncompressed file.");
                }
            }

            // Get file size
            $fileSize = $this->formatBytes(filesize($filepath));

            $this->info("✅ Database backup completed successfully!");
            $this->table(['Property', 'Value'], [
                ['Database', $config['database']],
                ['Backup File', basename($filepath)],
                ['File Size', $fileSize],
                ['Location', dirname($filepath)],
                ['Created At', Carbon::now()->format('Y-m-d H:i:s')]
            ]);

            // Store backup info in database
            $this->storeBackupInfo($filepath, $fileSize);

            return 0;

        } catch (\Exception $e) {
            $this->error('Backup failed with error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Build the mysqldump command
     */
    private function buildMysqlDumpCommand($config, $filepath)
    {
        $command = 'mysqldump';
        
        // Add connection parameters
        $command .= " --host={$config['host']}";
        $command .= " --port={$config['port']}";
        $command .= " --user={$config['username']}";
        
        if (!empty($config['password'])) {
            $command .= " --password=\"{$config['password']}\"";
        }

        // Add options for better backup
        $command .= " --single-transaction";
        $command .= " --routines";
        $command .= " --triggers";
        $command .= " --add-drop-table";
        $command .= " --extended-insert";
        $command .= " --create-options";

        // Handle specific tables if specified
        if ($this->option('tables')) {
            $tables = explode(',', $this->option('tables'));
            $tables = array_map('trim', $tables);
            $command .= " " . $config['database'] . " " . implode(' ', $tables);
        } else {
            $command .= " " . $config['database'];
        }

        $command .= " > \"{$filepath}\"";

        return $command;
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

    /**
     * Store backup information in database
     */
    private function storeBackupInfo($filepath, $fileSize)
    {
        try {
            DB::table('database_backups')->insert([
                'filename' => basename($filepath),
                'filepath' => $filepath,
                'file_size' => $fileSize,
                'database_name' => config('database.connections.' . config('database.default') . '.database'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // If table doesn't exist, create it
            $this->createBackupTable();
            
            // Try again
            DB::table('database_backups')->insert([
                'filename' => basename($filepath),
                'filepath' => $filepath,
                'file_size' => $fileSize,
                'database_name' => config('database.connections.' . config('database.default') . '.database'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Create backup tracking table
     */
    private function createBackupTable()
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS database_backups (
                id INT AUTO_INCREMENT PRIMARY KEY,
                filename VARCHAR(255) NOT NULL,
                filepath TEXT NOT NULL,
                file_size VARCHAR(50) NOT NULL,
                database_name VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
    }
}
