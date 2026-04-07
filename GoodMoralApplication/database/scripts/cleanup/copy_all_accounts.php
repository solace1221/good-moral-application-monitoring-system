<?php
// Copy all accounts from GoodMoralApp to db-clearance
try {
    $pdoSource = new PDO("mysql:host=127.0.0.1;dbname=GoodMoralApp", "root", "");
    $pdoTarget = new PDO("mysql:host=127.0.0.1;dbname=db-clearance", "root", "");
    
    $pdoSource->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdoTarget->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=================================================\n";
    echo "Copying All Accounts to db-clearance\n";
    echo "=================================================\n\n";
    
    // Get all accounts from GoodMoralApp
    $stmt = $pdoSource->query("SELECT * FROM role_account");
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($accounts) . " total accounts in GoodMoralApp\n\n";
    
    $copied = 0;
    $skipped = 0;
    
    foreach ($accounts as $account) {
        // Check if account already exists in target
        $checkStmt = $pdoTarget->prepare("SELECT id FROM role_account WHERE email = ?");
        $checkStmt->execute([$account['email']]);
        
        if ($checkStmt->fetch()) {
            $skipped++;
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
        
        $copied++;
    }
    
    echo "✓ COPIED: $copied accounts\n";
    echo "⊘ SKIPPED: $skipped accounts (already exist)\n";
    
    echo "\n=================================================\n";
    echo "Account Summary in db-clearance\n";
    echo "=================================================\n\n";
    
    $stmt = $pdoTarget->query("
        SELECT account_type, COUNT(*) as count 
        FROM role_account 
        GROUP BY account_type
    ");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("  %-20s: %d accounts\n", $row['account_type'], $row['count']);
    }
    
    echo "\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
