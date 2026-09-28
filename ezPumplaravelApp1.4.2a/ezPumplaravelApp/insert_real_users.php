<?php
/**
 * Insert real users from MySQL dump
 */

$dbPath = __DIR__ . '/database/database.sqlite';
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Deleting test user...\n";
try {
    $db->exec("DELETE FROM users WHERE email = 'admin@ezpump.com'");
    echo "✓ Deleted test user\n";
} catch (Exception $e) {
    echo "Note: " . $e->getMessage() . "\n";
}

echo "\nInserting real users from dump...\n\n";

// Get the users table structure
$result = $db->query("PRAGMA table_info(users)");
$columns = $result->fetchAll(PDO::FETCH_ASSOC);
echo "Users table has " . count($columns) . " columns:\n";
foreach ($columns as $col) {
    echo "  - " . $col['name'] . " (" . $col['type'] . ")\n";
}

echo "\nInserting users...\n";

try {
    // User 1: superadmin
    $stmt = $db->prepare("INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role, blocked_at, is_blocked) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        1,
        'superadmin',
        'superadmin@ez-pump.com',
        null,
        '$2y$12$fPuK32ORTLhrb/Xqfnoc1u4REFIhBEV1NRLCGmWxK7cEOTVP5b6L.',
        'jMz1vZZhIeg2YjuIEUnbH0FKPm8h6o0OqkMkxGqyZoX3tXQOMNppuMnT7YNC',
        '2025-09-26 12:15:52',
        '2025-09-26 12:15:52',
        0,
        null,
        0
    ]);
    echo "✓ Inserted: superadmin@ez-pump.com (Role: 0 - Super Admin)\n";
    
    // User 2: BE-04
    $stmt->execute([
        2,
        'BE-04',
        'BE-04@ez-pump.com',
        null,
        '$2y$12$.5lGLvjs0h9LiTiUe6OK7e4wkL7P35ZIWxOek0rG5UTHYsl3iBYHi',
        'DFYjSTHiwRR8aP7DA1crPRZ66SYvPheSUbYuTGJ7YDznglk9fbiolvgqiAGX',
        '2026-06-11 04:29:19',
        '2026-06-11 04:29:19',
        2,
        null,
        0
    ]);
    echo "✓ Inserted: BE-04@ez-pump.com (Role: 2)\n";
    
    // User 3: be-04
    $stmt->execute([
        3,
        'BE-04',
        'be-04@beenergy.com',
        null,
        '$2y$12$VdWEQx94w/n0Z5TlJlBfR.GVbcgwAjKXQDPwYfgdofTBr2VSRbwtS',
        'OKk0QFrdTbFJ9sTXlaX9rKSJBfSOfrZ4ni0jAgA8PohcS8v1rOJDo3LyAT91',
        '2026-06-11 05:47:16',
        '2026-06-11 05:47:16',
        2,
        null,
        0
    ]);
    echo "✓ Inserted: be-04@beenergy.com (Role: 2)\n";
    
    echo "\n✓ All users inserted successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Login Credentials:\n";
echo str_repeat("=", 60) . "\n";
echo "Email: superadmin@ez-pump.com\n";
echo "Password: KHSHUIS@SIDs!$\n";
echo str_repeat("=", 60) . "\n";
