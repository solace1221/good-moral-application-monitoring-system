<?php

// Create new database for Good Moral Application

try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `goodmoral_system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "✅ Database 'goodmoral_system' created successfully!\n\n";
    echo "Next steps:\n";
    echo "1. Update your .env file: DB_DATABASE=goodmoral_system\n";
    echo "2. Run: php artisan migrate\n";
    echo "3. Run: php artisan db:seed (if you have seeders)\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
