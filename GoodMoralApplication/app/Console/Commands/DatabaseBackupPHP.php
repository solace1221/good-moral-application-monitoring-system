<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DatabaseBackupPHP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup-php 
                            {--path= : Custom backup path}
                            {--compress : Compress the backup file}
                            {--tables= : Specific tables to backup (comma separated)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the database using PHP (no mysqldump required)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting PHP-based database backup...');

        try {
            // Get database configuration
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");

            if (!in_array($connection, ['mysql', 'mariadb'])) {
                $this->error('This backup command supports MySQL/MariaDB only.');
                return 1;
            }

            // Create backup directory if it doesn't exist
            $backupDir = $this->option('path') ?: storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Generate backup filename
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_php_{$config['database']}_{$timestamp}.sql";
            $filepath = $backupDir . '/' . $filename;

            $this->info("Creating backup file: {$filepath}");

            // Get tables to backup
            $tables = $this->getTablesToBackup();
            
            if (empty($tables)) {
                $this->error('No tables found to backup.');
                return 1;
            }

            $this->info("Found " . count($tables) . " tables to backup.");

            // Create backup file
            $backupContent = $this->generateBackupContent($tables);
            
            file_put_contents($filepath, $backupContent);

            // Compress if requested
            if ($this->option('compress')) {
                $this->info('Compressing backup file...');
                $compressedFile = $filepath . '.gz';
                
                $compressed = gzencode(file_get_contents($filepath), 9);
                file_put_contents($compressedFile, $compressed);
                
                if (file_exists($compressedFile)) {
                    unlink($filepath); // Remove uncompressed file
                    $filepath = $compressedFile;
                    $this->info("Backup compressed successfully!");
                }
            }

            // Get file size
            $fileSize = $this->formatBytes(filesize($filepath));

            $this->info("✅ PHP Database backup completed successfully!");
            $this->table(['Property', 'Value'], [
                ['Database', $config['database']],
                ['Backup File', basename($filepath)],
                ['File Size', $fileSize],
                ['Location', dirname($filepath)],
                ['Tables', count($tables)],
                ['Created At', Carbon::now()->format('Y-m-d H:i:s')]
            ]);

            // Store backup info in database
            $this->storeBackupInfo($filepath, $fileSize, count($tables));

            return 0;

        } catch (\Exception $e) {
            $this->error('Backup failed with error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Get tables to backup
     */
    private function getTablesToBackup()
    {
        if ($this->option('tables')) {
            $tables = explode(',', $this->option('tables'));
            return array_map('trim', $tables);
        }

        // Get all tables
        $tables = [];
        $results = DB::select('SHOW TABLES');
        
        foreach ($results as $result) {
            $tableArray = (array) $result;
            $tables[] = array_values($tableArray)[0];
        }

        return $tables;
    }

    /**
     * Generate backup content
     */
    private function generateBackupContent($tables)
    {
        $content = "-- PHP Database Backup\n";
        $content .= "-- Generated on: " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
        $content .= "-- Database: " . config('database.connections.' . config('database.default') . '.database') . "\n";
        $content .= "-- Tables: " . implode(', ', $tables) . "\n\n";
        
        $content .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $content .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $content .= "SET AUTOCOMMIT = 0;\n";
        $content .= "START TRANSACTION;\n";
        $content .= "SET time_zone = \"+00:00\";\n\n";

        foreach ($tables as $table) {
            $this->line("Processing table: {$table}");
            $content .= $this->getTableBackup($table);
        }

        $content .= "\nSET FOREIGN_KEY_CHECKS=1;\n";
        $content .= "COMMIT;\n";

        return $content;
    }

    /**
     * Get backup content for a single table
     */
    private function getTableBackup($table)
    {
        $content = "\n-- --------------------------------------------------------\n";
        $content .= "-- Table structure for table `{$table}`\n";
        $content .= "-- --------------------------------------------------------\n\n";

        // Drop table if exists
        $content .= "DROP TABLE IF EXISTS `{$table}`;\n";

        // Get table structure
        $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
        if (!empty($createTable)) {
            $content .= $createTable[0]->{'Create Table'} . ";\n\n";
        }

        // Get table data
        $content .= "-- Dumping data for table `{$table}`\n\n";
        
        $rows = DB::table($table)->get();
        
        if ($rows->count() > 0) {
            $content .= "INSERT INTO `{$table}` VALUES\n";
            
            $values = [];
            foreach ($rows as $row) {
                $rowArray = (array) $row;
                $escapedValues = [];
                
                foreach ($rowArray as $value) {
                    if ($value === null) {
                        $escapedValues[] = 'NULL';
                    } elseif (is_numeric($value)) {
                        $escapedValues[] = $value;
                    } else {
                        $escapedValues[] = "'" . addslashes($value) . "'";
                    }
                }
                
                $values[] = '(' . implode(',', $escapedValues) . ')';
            }
            
            $content .= implode(",\n", $values) . ";\n\n";
        }

        return $content;
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
    private function storeBackupInfo($filepath, $fileSize, $tableCount)
    {
        try {
            DB::table('database_backups')->insert([
                'filename' => basename($filepath),
                'filepath' => $filepath,
                'file_size' => $fileSize,
                'database_name' => config('database.connections.' . config('database.default') . '.database'),
                'table_count' => $tableCount,
                'backup_type' => 'PHP',
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
                'table_count' => $tableCount,
                'backup_type' => 'PHP',
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
                table_count INT DEFAULT 0,
                backup_type VARCHAR(20) DEFAULT 'mysqldump',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
    }
}
