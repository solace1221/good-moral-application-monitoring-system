<?php
// Check role_account in both databases
try {
    echo "=================================================\n";
    echo "GoodMoralApp - role_account Table\n";
    echo "=================================================\n";
    
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=GoodMoralApp", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("DESCRIBE role_account");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    
    echo "\n--- Admin Accounts ---\n";
    $stmt = $pdo->query("SELECT * FROM role_account WHERE account_type = 'admin' LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "\nID: " . $row['id'] . "\n";
        echo "Email: " . $row['email'] . "\n";
        echo "Name: " . ($row['firstname'] ?? '') . " " . ($row['lastname'] ?? '') . "\n";
        echo "Type: " . $row['account_type'] . "\n";
        echo "Student ID: " . ($row['student_id'] ?? 'N/A') . "\n";
    }
    
    echo "\n\n=================================================\n";
    echo "db-clearance - role_account Table\n";
    echo "=================================================\n";
    
    $pdo2 = new PDO("mysql:host=127.0.0.1;dbname=db-clearance", "root", "");
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo2->query("DESCRIBE role_account");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    
    echo "\n--- Admin Accounts ---\n";
    $stmt = $pdo2->query("SELECT * FROM role_account WHERE account_type = 'admin' LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "\nID: " . $row['id'] . "\n";
        echo "Email: " . $row['email'] . "\n";
        echo "Name: " . ($row['firstname'] ?? '') . " " . ($row['lastname'] ?? '') . "\n";
        echo "Type: " . $row['account_type'] . "\n";
        echo "Student ID: " . ($row['student_id'] ?? 'N/A') . "\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
