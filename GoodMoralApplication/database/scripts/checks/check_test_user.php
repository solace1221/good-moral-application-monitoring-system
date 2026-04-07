<?php
// Direct database check for test.user@gmail.com
$mysqli = new mysqli('localhost', 'root', '', 'db-clearance-system');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$email = 'test.user@gmail.com';

echo "=== Checking CMS for: $email ===\n\n";

// Check users table
$stmt = $mysqli->prepare("SELECT id, name, email, role, student_number, department_id, course_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo "✓ User found in CMS users table:\n";
    echo "  ID: " . $row['id'] . "\n";
    echo "  Name: " . $row['name'] . "\n";
    echo "  Email: " . $row['email'] . "\n";
    echo "  Role: " . ($row['role'] ?? 'NULL') . "\n";
    echo "  Student Number: " . ($row['student_number'] ?? 'NULL') . "\n";
    echo "  Department ID: " . ($row['department_id'] ?? 'NULL') . "\n";
    echo "  Course ID: " . ($row['course_id'] ?? 'NULL') . "\n\n";
    
    $userId = $row['id'];
    
    // Check for student record
    $stmt2 = $mysqli->prepare("SELECT id, student_number, users_id, department_id, course_id, year_level FROM students WHERE users_id = ?");
    $stmt2->bind_param("i", $userId);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    
    if ($row2 = $result2->fetch_assoc()) {
        echo "✓ Student record found:\n";
        echo "  Student ID: " . $row2['id'] . "\n";
        echo "  Student Number: " . $row2['student_number'] . "\n";
        echo "  Users ID: " . $row2['users_id'] . "\n";
        echo "  Department: " . $row2['department_id'] . "\n";
        echo "  Course: " . $row2['course_id'] . "\n";
        echo "  Year: " . $row2['year_level'] . "\n";
    } else {
        echo "✗ NO student record found for this user\n";
        echo "\nNeed to create student record!\n";
    }
} else {
    echo "✗ User NOT found in CMS\n";
    echo "\nChecking GMAMS for this user...\n\n";
    
    // Check GMAMS
    $stmt = $mysqli->prepare("SELECT student_id, fname, mname, lname, email, department, course, year_level FROM student_registrations WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo "✓ Found in GMAMS student_registrations:\n";
        echo "  Student ID: " . $row['student_id'] . "\n";
        echo "  Name: " . $row['fname'] . " " . ($row['mname'] ?? '') . " " . $row['lname'] . "\n";
        echo "  Department: " . $row['department'] . "\n";
        echo "  Course: " . $row['course'] . "\n";
        echo "  Year Level: " . $row['year_level'] . "\n";
        echo "\n✗ This user needs to be synced to CMS!\n";
    } else {
        echo "✗ User not found in GMAMS either\n";
    }
}

$mysqli->close();
echo "\n=== Check Complete ===\n";
