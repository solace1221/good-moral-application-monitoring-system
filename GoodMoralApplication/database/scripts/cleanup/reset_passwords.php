<?php
// Check and reset passwords
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=db-clearance", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=================================================\n";
    echo "Password Reset Tool\n";
    echo "=================================================\n\n";
    
    $testAccounts = [
        'admin@admin.com' => 'admin',
        'student@admin.com' => 'student',
        'dean@admin.com' => 'dean'
    ];
    
    foreach ($testAccounts as $email => $role) {
        echo "Processing: $email\n";
        echo "-----------------------------------\n";
        
        $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Set password to "password123"
            $newPassword = 'password123';
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            
            // Update password
            $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->execute([$hashedPassword, $user['id']]);
            
            // Verify it was set correctly
            $verifyStmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $verifyStmt->execute([$user['id']]);
            $newHash = $verifyStmt->fetch(PDO::FETCH_ASSOC)['password'];
            
            if (password_verify($newPassword, $newHash)) {
                echo "✓ Password set to: password123\n";
                echo "  Hash: " . substr($newHash, 0, 30) . "...\n";
            } else {
                echo "✗ Password verification failed!\n";
            }
        } else {
            echo "✗ User not found!\n";
        }
        
        echo "\n";
    }
    
    echo "=================================================\n";
    echo "All passwords reset to: password123\n";
    echo "=================================================\n\n";
    
    echo "Try logging in with:\n";
    echo "  Email: admin@admin.com\n";
    echo "  Password: password123\n\n";
    
    // Also check if there are any other admin users
    echo "All admin users:\n";
    $stmt = $pdo->query("SELECT id, email, role, name FROM users WHERE role = 'admin'");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - " . $row['email'] . " (ID: " . $row['id'] . ")\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
