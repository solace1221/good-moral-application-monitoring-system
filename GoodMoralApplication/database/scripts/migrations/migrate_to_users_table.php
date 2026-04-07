<?php
/**
 * Migrate accounts from role_account to users table
 * Maps account_type to role column
 */

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=db-clearance", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=================================================\n";
    echo "Migrating role_account to users table\n";
    echo "=================================================\n\n";
    
    // Get all accounts from role_account
    $stmt = $pdo->query("SELECT * FROM role_account");
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($accounts) . " accounts in role_account\n\n";
    
    $inserted = 0;
    $updated = 0;
    $skipped = 0;
    $errors = 0;
    
    foreach ($accounts as $account) {
        try {
            // Skip if no email
            if (empty($account['email'])) {
                $skipped++;
                continue;
            }
            
            // Check if user already exists
            $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $checkStmt->execute([$account['email']]);
            $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            // Map account_type to role
            // account_type values: admin, student, alumni, dean, head_osa, sec_osa, registrar, psg_officer, prog_coor
            // role enum: student, officer, employee, adviser, admin, dean, program_coordinator
            $roleMapping = [
                'admin' => 'admin',
                'student' => 'student',
                'alumni' => 'student', // Map alumni to student
                'dean' => 'dean',
                'deanSITE' => 'dean',
                'deanSASTE' => 'dean',
                'deanSBAHM' => 'dean',
                'deanSNAHS' => 'dean',
                'deangradsch' => 'dean',
                'deansom' => 'dean',
                'head_osa' => 'officer',
                'sec_osa' => 'officer',
                'registrar' => 'employee',
                'psg_officer' => 'officer',
                'prog_coor' => 'program_coordinator'
            ];
            
            $role = $roleMapping[$account['account_type']] ?? 'student';
            
            // Prepare name fields
            $name = $account['fullname'] ?? '';
            $firstname = '';
            $lastname = '';
            $middlename = $account['mname'] ?? null;
            
            // Try to split fullname into first and last name
            if (!empty($name)) {
                $nameParts = explode(',', $name);
                if (count($nameParts) >= 2) {
                    $lastname = trim($nameParts[0]);
                    $firstname = trim($nameParts[1]);
                } else {
                    $nameParts = explode(' ', $name);
                    $firstname = $nameParts[0] ?? '';
                    $lastname = $nameParts[count($nameParts) - 1] ?? '';
                }
            }
            
            if ($existingUser) {
                // Update existing user
                $updateStmt = $pdo->prepare("
                    UPDATE users SET
                        role = ?,
                        student_id = ?,
                        password = ?,
                        firstname = ?,
                        lastname = ?,
                        middlename = ?,
                        gender = ?,
                        course_id = ?,
                        year_level = ?,
                        organization = ?,
                        position_name = ?,
                        is_graduating = ?,
                        graduation_date = ?,
                        graduated_at = ?,
                        status = ?,
                        updated_at = NOW()
                    WHERE email = ?
                ");
                
                $updateStmt->execute([
                    $role,
                    $account['student_id'],
                    $account['password'],
                    $firstname,
                    $lastname,
                    $middlename,
                    $account['gender'],
                    null, // course_id - will need manual mapping if needed
                    $account['year_level'],
                    $account['organization'],
                    $account['position'],
                    $account['is_graduating'] ?? 0,
                    $account['graduation_date'],
                    $account['graduated_at'],
                    $account['status'] ? 'active' : 'inactive',
                    $account['email']
                ]);
                
                echo "✓ UPDATED: " . $account['email'] . " (" . $account['account_type'] . " → " . $role . ")\n";
                $updated++;
            } else {
                // Insert new user
                $insertStmt = $pdo->prepare("
                    INSERT INTO users 
                    (name, firstname, lastname, middlename, gender, email, password, role, 
                     student_id, year_level, organization, position_name, is_graduating, 
                     graduation_date, graduated_at, status, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $insertStmt->execute([
                    $name,
                    $firstname,
                    $lastname,
                    $middlename,
                    $account['gender'],
                    $account['email'],
                    $account['password'],
                    $role,
                    $account['student_id'],
                    $account['year_level'],
                    $account['organization'],
                    $account['position'],
                    $account['is_graduating'] ?? 0,
                    $account['graduation_date'],
                    $account['graduated_at'],
                    $account['status'] ? 'active' : 'inactive',
                    $account['created_at'] ?? date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s')
                ]);
                
                echo "✓ INSERTED: " . $account['email'] . " (" . $account['account_type'] . " → " . $role . ")\n";
                $inserted++;
            }
            
        } catch (PDOException $e) {
            echo "✗ ERROR processing " . ($account['email'] ?? 'unknown') . ": " . $e->getMessage() . "\n";
            $errors++;
        }
    }
    
    echo "\n=================================================\n";
    echo "Migration Complete!\n";
    echo "=================================================\n";
    echo "✓ Inserted: $inserted users\n";
    echo "✓ Updated:  $updated users\n";
    echo "⊘ Skipped:  $skipped users (no email)\n";
    echo "✗ Errors:   $errors users\n";
    echo "=================================================\n\n";
    
    // Show summary
    echo "User Summary by Role:\n";
    echo "---------------------\n";
    $stmt = $pdo->query("SELECT role, COUNT(*) as count FROM users GROUP BY role ORDER BY count DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("  %-20s: %d\n", $row['role'], $row['count']);
    }
    
    echo "\n✓ You can now use 'users' table for authentication!\n\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
