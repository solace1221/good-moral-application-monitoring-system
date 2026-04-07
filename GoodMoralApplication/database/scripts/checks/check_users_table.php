<?php
// Check users table structure
$mysqli = new mysqli('localhost', 'root', '', 'db-clearance-system');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== CMS Users Table Structure ===\n\n";

$result = $mysqli->query("SHOW COLUMNS FROM users");
echo "Columns:\n";
while ($row = $result->fetch_assoc()) {
    echo "  - {$row['Field']} ({$row['Type']})\n";
}

$mysqli->close();
