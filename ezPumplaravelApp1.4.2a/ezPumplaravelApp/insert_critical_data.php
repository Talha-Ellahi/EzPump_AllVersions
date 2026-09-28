<?php
/**
 * Insert critical configuration data
 */

$dbPath = __DIR__ . '/database/database.sqlite';
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Inserting critical configuration data...\n\n";

// Insert SysConfig
try {
    $db->exec("INSERT INTO SysConfig VALUES (1,1,2,1,2,21,4140,'02:81:3c:39:75:34',1,4,1,8,0,8,3,3,1,3,0,0,1,'2026-05-09 09:00:00',6,0,0,0,0)");
    echo "✓ Inserted SysConfig\n";
} catch (Exception $e) {
    echo "✗ SysConfig: " . $e->getMessage() . "\n";
}

// Insert ATGs
try {
    $db->exec("INSERT INTO ATGs VALUES (1,4,1,1,2,27,1,1,1)");
    $db->exec("INSERT INTO ATGs VALUES (2,4,1,1,2,35,2,2,1)");
    $db->exec("INSERT INTO ATGs VALUES (3,4,1,1,2,34,3,3,1)");
    echo "✓ Inserted ATGs (3 rows)\n";
} catch (Exception $e) {
    echo "✗ ATGs: " . $e->getMessage() . "\n";
}

// Insert DEVICES
try {
    $db->exec("INSERT INTO DEVICES VALUES (1,2,3,1,1,1,'none',1,2,2)");
    $db->exec("INSERT INTO DEVICES VALUES (2,2,3,1,1,1,'none',1,2,4)");
    $db->exec("INSERT INTO DEVICES VALUES (3,2,3,1,1,1,'none',1,2,2)");
    $db->exec("INSERT INTO DEVICES VALUES (4,3,3,2,1,1,'none',1,2,3)");
    echo "✓ Inserted DEVICES (4 rows)\n";
} catch (Exception $e) {
    echo "✗ DEVICES: " . $e->getMessage() . "\n";
}

// Insert PUMPS
try {
    $db->exec("INSERT INTO PUMPS VALUES (1,1,1,'PUMP-1',2,1,1,2,1,1)");
    $db->exec("INSERT INTO PUMPS VALUES (2,1,2,'PUMP-2',4,2,2,2,1,1)");
    $db->exec("INSERT INTO PUMPS VALUES (3,2,1,'PUMP-3',2,1,1,2,1,1)");
    $db->exec("INSERT INTO PUMPS VALUES (4,2,2,'PUMP-4',4,2,2,2,1,1)");
    $db->exec("INSERT INTO PUMPS VALUES (5,3,1,'PUMP-5',2,1,1,2,1,1)");
    $db->exec("INSERT INTO PUMPS VALUES (6,3,2,'PUMP-6',4,2,2,2,1,1)");
    $db->exec("INSERT INTO PUMPS VALUES (7,1,3,'PUMP-7',4,1,1,2,1,1)");
    $db->exec("INSERT INTO PUMPS VALUES (8,1,4,'PUMP-8',4,1,1,2,1,1)");
    echo "✓ Inserted PUMPS (8 rows)\n";
} catch (Exception $e) {
    echo "✗ PUMPS: " . $e->getMessage() . "\n";
}

// Insert PUMP_STATE
try {
    $db->exec("INSERT INTO PUMP_STATE VALUES (1,1,0,0,0,0,'2026-06-10 10:17:51')");
    $db->exec("INSERT INTO PUMP_STATE VALUES (2,2,0,0,0,0,'2026-06-10 10:17:51')");
    $db->exec("INSERT INTO PUMP_STATE VALUES (3,3,0,0,0,0,'2026-06-10 10:17:51')");
    $db->exec("INSERT INTO PUMP_STATE VALUES (4,4,0,0,0,0,'2026-06-10 10:17:51')");
    $db->exec("INSERT INTO PUMP_STATE VALUES (5,5,0,0,0,0,'2026-06-10 10:17:51')");
    $db->exec("INSERT INTO PUMP_STATE VALUES (6,6,0,0,0,0,'2026-06-10 10:17:51')");
    $db->exec("INSERT INTO PUMP_STATE VALUES (7,7,0,0,0,0,'2026-06-10 10:17:51')");
    $db->exec("INSERT INTO PUMP_STATE VALUES (8,8,0,0,0,0,'2026-06-10 10:17:51')");
    echo "✓ Inserted PUMP_STATE (8 rows)\n";
} catch (Exception $e) {
    echo "✗ PUMP_STATE: " . $e->getMessage() . "\n";
}

// Insert tanks
try {
    $db->exec("INSERT INTO tanks VALUES (1,'TANK-1',1,1,2000000,1500000,100000,500000,1,'HSD')");
    $db->exec("INSERT INTO tanks VALUES (2,'TANK-2',2,2,2000000,1500000,100000,500000,1,'PMG')");
    $db->exec("INSERT INTO tanks VALUES (3,'TANK-3',3,3,2000000,1500000,100000,500000,1,'HOBC')");
    echo "✓ Inserted tanks (3 rows)\n";
} catch (Exception $e) {
    echo "✗ tanks: " . $e->getMessage() . "\n";
}

// Insert a default user (admin)
try {
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $db->exec("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES ('Admin', 'admin@ezpump.com', '$password', 0, datetime('now'), datetime('now'))");
    echo "✓ Inserted default admin user (email: admin@ezpump.com, password: admin123)\n";
} catch (Exception $e) {
    echo "✗ users: " . $e->getMessage() . "\n";
}

// Insert paymentmethod
try {
    $db->exec("INSERT INTO paymentmethod VALUES (1,'Cash','cash_image.png',1,1,NULL,NULL)");
    $db->exec("INSERT INTO paymentmethod VALUES (2,'Bank Transfer','bank_image.png',0,1,NULL,NULL)");
    $db->exec("INSERT INTO paymentmethod VALUES (3,'Credit','credit_image.png',0,1,NULL,NULL)");
    echo "✓ Inserted paymentmethod (3 rows)\n";
} catch (Exception $e) {
    echo "✗ paymentmethod: " . $e->getMessage() . "\n";
}

// Insert NWK_Config
try {
    $db->exec("INSERT INTO NWK_Config VALUES (1,'N/A','192.168.8.1','192.168.8.100','255.255.255.0','192.168.8.1')");
    echo "✓ Inserted NWK_Config\n";
} catch (Exception $e) {
    echo "✗ NWK_Config: " . $e->getMessage() . "\n";
}

// Insert TAGS_STATUS  
try {
    $db->exec("INSERT INTO TAGS_STATUS VALUES (1,0,'2026-06-10 10:17:51')");
    $db->exec("INSERT INTO TAGS_STATUS VALUES (2,0,'2026-06-10 10:17:51')");
    $db->exec("INSERT INTO TAGS_STATUS VALUES (3,0,'2026-06-10 10:17:51')");
    $db->exec("INSERT INTO TAGS_STATUS VALUES (4,0,'2026-06-10 10:17:51')");
    echo "✓ Inserted TAGS_STATUS (4 rows)\n";
} catch (Exception $e) {
    echo "✗ TAGS_STATUS: " . $e->getMessage() . "\n";
}

echo "\n✓ Critical data inserted successfully!\n";
echo "\nYou can now log in with:\n";
echo "  Email: admin@ezpump.com\n";
echo "  Password: admin123\n";
