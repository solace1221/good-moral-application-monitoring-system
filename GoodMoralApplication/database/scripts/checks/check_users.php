<?php
// Check users in db-clearance database
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=db-clearance", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=================================================\n";
    echo "Users in db-clearance Database\n";
    echo "=================================================\n\n";
    
    $stmt = $pdo->query("SELECT id, name, email, account_type, created_at FROM users ORDER BY id");
    
    echo sprintf("%-5s %-30s %-35s %-15s\n", "ID", "Name", "Email", "Account Type");
    echo str_repeat("-", 90) . "\n";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("%-5s %-30s %-35s %-15s\n", 
            $row['id'],
            substr($row['name'], 0, 30),
            substr($row['email'], 0, 35),
            $row['account_type'] ?? 'N/A'
        );
    }
    
    echo "\n=================================================\n";
    echo "Admin/Staff Users:\n";
    echo "=================================================\n";
    
    $stmt = $pdo->query("SELECT id, name, email, account_type FROM users WHERE account_type IN ('admin', 'head_osa', 'sec_osa', 'dean', 'registrar', 'psg_officer') ORDER BY account_type");
    
    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "\n  Email: " . $row['email'] . "\n";
        echo "  Name: " . $row['name'] . "\n";
        echo "  Type: " . $row['account_type'] . "\n";
        $count++;
    }
    
    if ($count == 0) {
        echo "\n  ⚠️  No admin users found!\n";
    }
    
    echo "\n=================================================\n\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
