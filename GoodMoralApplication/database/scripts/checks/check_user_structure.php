<?php
// Check user structure and data
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=db-clearance", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=================================================\n";
    echo "Users Table Structure (db-clearance)\n";
    echo "=================================================\n";
    $stmt = $pdo->query("DESCRIBE users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    
    echo "\n=================================================\n";
    echo "All Users in db-clearance\n";
    echo "=================================================\n";
    $stmt = $pdo->query("SELECT * FROM users LIMIT 10");
    
    $first = true;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($first) {
            echo "\nColumns: " . implode(", ", array_keys($row)) . "\n\n";
            $first = false;
        }
        echo "ID: " . $row['id'] . "\n";
        echo "Email: " . ($row['email'] ?? 'N/A') . "\n";
        echo "Name: " . ($row['name'] ?? $row['firstname'] ?? 'N/A') . "\n";
        echo "Role: " . ($row['role'] ?? 'N/A') . "\n";
        echo "---\n";
    }
    
    echo "\n=================================================\n";
    echo "role_account Table (if exists)\n";
    echo "=================================================\n";
    
    try {
        $stmt = $pdo->query("SELECT * FROM role_account LIMIT 10");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "\nID: " . $row['id'] . "\n";
            foreach ($row as $key => $value) {
                echo "  $key: $value\n";
            }
            echo "---\n";
        }
    } catch (PDOException $e) {
        echo "role_account table not found or error: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
