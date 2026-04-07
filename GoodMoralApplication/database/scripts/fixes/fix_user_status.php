<?php
// Update all user statuses to 'active'
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=db-clearance", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Updating user statuses to 'active'...\n\n";
    
    $stmt = $pdo->exec("UPDATE users SET status = 'active' WHERE status IS NULL OR status = ''");
    
    echo "✓ Updated users to active status\n";
    
    // Show user statuses
    $stmt = $pdo->query("SELECT role, status, COUNT(*) as count FROM users GROUP BY role, status");
    
    echo "\nUser Status Summary:\n";
    echo "---------------------\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("  %-20s %-10s: %d\n", $row['role'], $row['status'] ?? 'NULL', $row['count']);
    }
    
    echo "\n✓ All users ready for login!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
