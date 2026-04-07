<?php
// Quick database check
try {
    $pdo = new PDO("mysql:host=127.0.0.1", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Databases:\n";
    $stmt = $pdo->query("SHOW DATABASES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "  - " . $row[0] . "\n";
    }
    
    echo "\nTables in GoodMoralApp:\n";
    $stmt = $pdo->query("SHOW TABLES FROM GoodMoralApp");
    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "  - " . $row[0] . "\n";
        $count++;
    }
    echo "Total: $count tables\n";
    
    echo "\nTables in db-clearance:\n";
    $stmt = $pdo->query("SHOW TABLES FROM `db-clearance`");
    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "  - " . $row[0] . "\n";
        $count++;
    }
    echo "Total: $count tables\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
