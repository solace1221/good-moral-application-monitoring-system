<?php
// Check GoodMoralApp users
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=GoodMoralApp", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=================================================\n";
    echo "GoodMoralApp Users Table Structure\n";
    echo "=================================================\n";
    $stmt = $pdo->query("DESCRIBE users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    
    echo "\n=================================================\n";
    echo "Admin Users in GoodMoralApp\n";
    echo "=================================================\n";
    $stmt = $pdo->query("SELECT id, name, email, account_type FROM users WHERE account_type = 'admin' OR email LIKE '%admin%'");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "\nID: " . $row['id'] . "\n";
        echo "Email: " . $row['email'] . "\n";
        echo "Name: " . $row['name'] . "\n";
        echo "Type: " . $row['account_type'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
