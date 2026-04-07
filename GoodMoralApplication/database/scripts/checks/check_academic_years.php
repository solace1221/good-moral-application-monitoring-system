<?php
// Quick check of academic_years table structure
$mysqli = new mysqli('localhost', 'root', '', 'db-clearance-system');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== Academic Years Table Structure ===\n\n";

// Show columns
$result = $mysqli->query("SHOW COLUMNS FROM academic_years");
echo "Columns:\n";
while ($row = $result->fetch_assoc()) {
    echo "  - {$row['Field']} ({$row['Type']})\n";
}

// Show data
echo "\nCurrent academic years:\n";
$result = $mysqli->query("SELECT * FROM academic_years");
while ($row = $result->fetch_assoc()) {
    echo "  ID: {$row['id']}, Year: {$row['year']}, Semester: {$row['semester']}\n";
}

if ($result->num_rows == 0) {
    echo "  (No academic years found)\n";
}

$mysqli->close();
