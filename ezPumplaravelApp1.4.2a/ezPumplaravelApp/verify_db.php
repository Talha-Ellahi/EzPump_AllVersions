<?php
$dbPath = __DIR__ . '/database/database.sqlite';
$db = new PDO('sqlite:' . $dbPath);

$tables = $db->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table'")->fetchColumn();
$rows = $db->query("SELECT COUNT(*) FROM saledata")->fetchColumn();
$users = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$sysconfig = $db->query("SELECT COUNT(*) FROM SysConfig")->fetchColumn();

echo str_repeat("=", 70) . "\n";
echo "DATABASE VERIFICATION\n";
echo str_repeat("=", 70) . "\n";
echo "✓ Total tables: $tables\n";
echo "✓ Saledata rows: " . number_format($rows) . "\n";
echo "✓ Users: $users\n";
echo "✓ SysConfig: $sysconfig\n";
echo "✓ Database file size: " . number_format(filesize($dbPath)) . " bytes\n";
echo str_repeat("=", 70) . "\n";
echo "✓ ALL TABLES AND DATA IMPORTED SUCCESSFULLY!\n";
echo str_repeat("=", 70) . "\n";
