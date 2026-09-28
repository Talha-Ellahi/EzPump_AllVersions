<?php
$dbPath = __DIR__ . '/database/database.sqlite';
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Creating SysParams table...\n";

$sql = "CREATE TABLE SysParams (
  id INTEGER NOT NULL,
  date TEXT DEFAULT NULL,
  uptime TEXT DEFAULT NULL,
  cpuload TEXT DEFAULT NULL,
  temp TEXT DEFAULT NULL,
  rom_usage TEXT DEFAULT NULL,
  ram_usage TEXT DEFAULT NULL,
  lan_ip TEXT DEFAULT NULL,
  wan_ip TEXT DEFAULT NULL,
  current_source INTEGER NOT NULL,
  \"4g_data_available\" INTEGER NOT NULL,
  fixed_interface_available INTEGER DEFAULT NULL,
  wireless_interface_available INTEGER DEFAULT NULL,
  modem_interface_available INTEGER DEFAULT NULL,
  created_at TEXT DEFAULT NULL,
  updated_at TEXT DEFAULT NULL
)";

try {
    $db->exec($sql);
    echo "✓ Table created\n";
    
    // Insert data
    $insertSql = "INSERT INTO SysParams VALUES (1,'2026-09-17 08:03:54','16:40','137%','47°C','49% of 7.0G','57% of 483M','192.168.2.100, 192.168.8.100, 10.10.23.21 ','202.163.87.237',1,1,1,0,1,'2026-06-09 17:32:11','2026-06-09 17:32:11')";
    
    $db->exec($insertSql);
    echo "✓ Data inserted\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

// Final count
$result = $db->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table'");
echo "\n✓ Total tables in database: " . $result->fetchColumn() . "\n";
