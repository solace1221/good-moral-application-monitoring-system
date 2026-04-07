<?php
// Database connection details
$host = 'localhost';
$dbname = 'goodmoral_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to database: $dbname\n\n";

    // Check if designations table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'designations'");
    if ($stmt->rowCount() > 0) {
        echo "=== DESIGNATIONS TABLE EXISTS ===\n";
        $stmt = $pdo->query("DESCRIBE designations");
        $designationsColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($designationsColumns as $column) {
            echo "- {$column['Field']} ({$column['Type']}) {$column['Key']}\n";
        }
    } else {
        echo "✗ DESIGNATIONS table does not exist\n";
    }
    
    echo "\n";
    
    // Check if positions table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'positions'");
    if ($stmt->rowCount() > 0) {
        echo "=== POSITIONS TABLE EXISTS ===\n";
        $stmt = $pdo->query("DESCRIBE positions");
        $positionsColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($positionsColumns as $column) {
            echo "- {$column['Field']} ({$column['Type']}) {$column['Key']}\n";
        }
    } else {
        echo "✗ POSITIONS table does not exist\n";
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
