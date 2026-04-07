<?php
// Copy admin accounts from GoodMoralApp to db-clearance
try {
    $pdoSource = new PDO("mysql:host=127.0.0.1;dbname=GoodMoralApp", "root", "");
    $pdoTarget = new PDO("mysql:host=127.0.0.1;dbname=db-clearance", "root", "");
    
    $pdoSource->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdoTarget->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=================================================\n";
    echo "Copying Admin Accounts to db-clearance\n";
    echo "=================================================\n\n";
    
    // Get all admin/staff accounts from GoodMoralApp
    $stmt = $pdoSource->query("
        SELECT * FROM role_account 
        WHERE account_type IN ('admin', 'head_osa', 'sec_osa', 'dean', 'registrar', 'psg_officer')
    ");
    
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($accounts) . " admin/staff accounts in GoodMoralApp\n\n";
    
    foreach ($accounts as $account) {
        // Check if account already exists in target
        $checkStmt = $pdoTarget->prepare("SELECT id FROM role_account WHERE email = ?");
        $checkStmt->execute([$account['email']]);
        
        if ($checkStmt->fetch()) {
            echo "SKIPPED: " . $account['email'] . " (already exists)\n";
            continue;
        }
        
        // Insert into db-clearance (only common columns)
        $insertStmt = $pdoTarget->prepare("
            INSERT INTO role_account 
            (fullname, email, student_id, department, password, account_type, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $insertStmt->execute([
            $account['fullname'],
            $account['email'],
            $account['student_id'],
            $account['department'],
            $account['password'],
            $account['account_type'],
            $account['status'],
            $account['created_at'],
            $account['updated_at']
        ]);
        
        echo "✓ COPIED: " . $account['email'] . " (" . $account['account_type'] . ")\n";
    }
    
    echo "\n=================================================\n";
    echo "Migration Complete!\n";
    echo "=================================================\n\n";
    
    // Show admin accounts in db-clearance
    echo "Admin accounts now in db-clearance:\n\n";
    $stmt = $pdoTarget->query("
        SELECT email, account_type, fullname 
        FROM role_account 
        WHERE account_type IN ('admin', 'head_osa', 'sec_osa', 'dean', 'registrar', 'psg_officer')
    ");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  Email: " . $row['email'] . "\n";
        echo "  Type: " . $row['account_type'] . "\n";
        echo "  Name: " . ($row['fullname'] ?? 'N/A') . "\n";
        echo "  ---\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
