<?php
// Check sessions table
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=db-clearance", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking sessions table...\n\n";
    
    // Check if sessions table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'sessions'");
    $exists = $stmt->fetch();
    
    if ($exists) {
        echo "✓ sessions table EXISTS\n";
        
        // Check structure
        $stmt = $pdo->query("DESCRIBE sessions");
        echo "\nTable structure:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
        
        // Count sessions
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM sessions");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "\nTotal sessions: " . $count['count'] . "\n";
        
    } else {
        echo "✗ sessions table DOES NOT EXIST!\n";
        echo "\nCreating sessions table...\n";
        
        $createSql = "
        CREATE TABLE `sessions` (
          `id` varchar(255) NOT NULL,
          `user_id` bigint(20) unsigned DEFAULT NULL,
          `ip_address` varchar(45) DEFAULT NULL,
          `user_agent` text,
          `payload` longtext NOT NULL,
          `last_activity` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `sessions_user_id_index` (`user_id`),
          KEY `sessions_last_activity_index` (`last_activity`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createSql);
        echo "✓ sessions table created!\n";
    }
    
    echo "\n✓ Session storage is ready!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
