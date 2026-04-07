<?php

/**
 * Automatic Database Migration Script
 * Migrates all tables from GoodMoralApp to db-clearance
 */

$sourceDb = 'GoodMoralApp';
$targetDb = 'db-clearance';
$host = '127.0.0.1';
$username = 'root';
$password = '';

echo "=================================================\n";
echo "Database Migration Tool (Auto Mode)\n";
echo "=================================================\n";
echo "Source: $sourceDb\n";
echo "Target: $targetDb\n";
echo "=================================================\n\n";

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to MySQL\n\n";
    
    // Get all tables from source database
    echo "Fetching tables from $sourceDb...\n";
    $stmt = $pdo->query("SHOW TABLES FROM `$sourceDb`");
    $sourceTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($sourceTables)) {
        echo "✗ No tables found in $sourceDb\n";
        exit(1);
    }
    
    echo "✓ Found " . count($sourceTables) . " tables in $sourceDb\n\n";
    
    // Get all tables from target database
    $stmt = $pdo->query("SHOW TABLES FROM `$targetDb`");
    $targetTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "✓ Found " . count($targetTables) . " existing tables in $targetDb\n\n";
    
    echo "=================================================\n";
    echo "Starting Migration...\n";
    echo "=================================================\n\n";
    
    $migrated = 0;
    $skipped = 0;
    $errors = 0;
    
    foreach ($sourceTables as $table) {
        echo "Processing: $table ... ";
        
        try {
            // Check if table exists in target
            if (in_array($table, $targetTables)) {
                echo "SKIPPED (already exists)\n";
                $skipped++;
                continue;
            }
            
            // Get CREATE TABLE statement
            $stmt = $pdo->query("SHOW CREATE TABLE `$sourceDb`.`$table`");
            $createStmt = $stmt->fetch(PDO::FETCH_ASSOC);
            $createSql = $createStmt['Create Table'];
            
            // Create table in target database
            $pdo->exec("USE `$targetDb`");
            $pdo->exec($createSql);
            
            // Copy data
            $pdo->exec("INSERT INTO `$targetDb`.`$table` SELECT * FROM `$sourceDb`.`$table`");
            
            // Get row count
            $stmt = $pdo->query("SELECT COUNT(*) FROM `$targetDb`.`$table`");
            $count = $stmt->fetchColumn();
            
            echo "✓ MIGRATED ($count rows)\n";
            $migrated++;
            
        } catch (PDOException $e) {
            echo "✗ ERROR: " . $e->getMessage() . "\n";
            $errors++;
        }
    }
    
    echo "\n";
    echo "=================================================\n";
    echo "Migration Complete!\n";
    echo "=================================================\n";
    echo "✓ Migrated: $migrated tables\n";
    echo "⊘ Skipped:  $skipped tables (already exist)\n";
    echo "✗ Errors:   $errors tables\n";
    echo "=================================================\n\n";
    
    if ($errors > 0) {
        echo "⚠️  Some tables had errors. Please review above.\n\n";
        exit(1);
    } else {
        echo "✓ All operations completed successfully!\n";
        echo "✓ Both systems can now use db-clearance database\n\n";
        exit(0);
    }
    
} catch (PDOException $e) {
    echo "✗ Connection Error: " . $e->getMessage() . "\n";
    exit(1);
}
