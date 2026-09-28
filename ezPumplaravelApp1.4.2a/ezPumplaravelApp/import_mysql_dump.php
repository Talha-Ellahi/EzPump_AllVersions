<?php
/**
 * Import MySQL dump into SQLite - Better approach using PDO
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$mysqlDumpFile = 'C:\Users\TalhaElahi\Downloads\DBs dump\id21dump170926.sql\id21dump170926.sql';

echo "Reading MySQL dump file...\n";
$content = file_get_contents($mysqlDumpFile);

// Split into statements
$statements = [];
$currentStatement = '';
$inComment = false;

$lines = explode("\n", $content);
foreach ($lines as $line) {
    $line = trim($line);
    
    // Skip comments
    if (empty($line) || strpos($line, '--') === 0 || strpos($line, '/*') === 0) {
        continue;
    }
    
    // Skip MySQL-specific commands
    if (preg_match('/^(SET|LOCK|UNLOCK|DROP|ALTER TABLE.*DISABLE|ALTER TABLE.*ENABLE)/i', $line)) {
        continue;
    }
    
    $currentStatement .= ' ' . $line;
    
    // Check if statement is complete
    if (substr($line, -1) === ';') {
        $statements[] = trim($currentStatement);
        $currentStatement = '';
    }
}

echo "Processing " . count($statements) . " statements...\n";

// Connect to SQLite
$dbPath = __DIR__ . '/database/database.sqlite';
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec('PRAGMA foreign_keys = OFF');
$db->exec('BEGIN TRANSACTION');

$successCount = 0;
$errorCount = 0;
$tableCount = 0;
$insertCount = 0;

foreach ($statements as $statement) {
    if (empty(trim($statement))) {
        continue;
    }
    
    // Convert MySQL syntax to SQLite
    $statement = preg_replace('/\btinyint\(\d+\)/i', 'INTEGER', $statement);
    $statement = preg_replace('/\bsmallint\(\d+\)/i', 'INTEGER', $statement);
    $statement = preg_replace('/\bmediumint\(\d+\)/i', 'INTEGER', $statement);
    $statement = preg_replace('/\bint\(\d+\)/i', 'INTEGER', $statement);
    $statement = preg_replace('/\bbigint\(\d+\)/i', 'INTEGER', $statement);
    $statement = preg_replace('/\bfloat\(\d+,\d+\)/i', 'REAL', $statement);
    $statement = preg_replace('/\bdouble\(\d+,\d+\)/i', 'REAL', $statement);
    $statement = preg_replace('/\bdecimal\(\d+,\d+\)/i', 'REAL', $statement);
    $statement = preg_replace('/\bvarchar\(\d+\)/i', 'TEXT', $statement);
    $statement = preg_replace('/\bchar\(\d+\)/i', 'TEXT', $statement);
    $statement = preg_replace('/\btext\b/i', 'TEXT', $statement);
    $statement = preg_replace('/\blongtext\b/i', 'TEXT', $statement);
    $statement = preg_replace('/\bmediumtext\b/i', 'TEXT', $statement);
    $statement = preg_replace('/\btinytext\b/i', 'TEXT', $statement);
    $statement = preg_replace('/\bdatetime\b/i', 'TEXT', $statement);
    $statement = preg_replace('/\btimestamp\b/i', 'TEXT', $statement);
    $statement = preg_replace('/\bdate\b/i', 'TEXT', $statement);
    $statement = preg_replace('/\btime\b/i', 'TEXT', $statement);
    
    // Remove AUTO_INCREMENT, UNSIGNED, ZEROFILL
    $statement = preg_replace('/\bAUTO_INCREMENT\b/i', '', $statement);
    $statement = preg_replace('/\bUNSIGNED\b/i', '', $statement);
    $statement = preg_replace('/\bZEROFILL\b/i', '', $statement);
    
    // Remove ENGINE and CHARSET
    $statement = preg_replace('/\s+ENGINE\s*=\s*\w+/i', '', $statement);
    $statement = preg_replace('/\s+AUTO_INCREMENT\s*=\s*\d+/i', '', $statement);
    $statement = preg_replace('/\s+DEFAULT\s+CHARSET\s*=\s*\w+/i', '', $statement);
    $statement = preg_replace('/\s+COLLATE\s*=\s*\w+/i', '', $statement);
    $statement = preg_replace('/\s+COLLATE\s+\w+/i', '', $statement);
    $statement = preg_replace('/\s+CHARACTER\s+SET\s+\w+/i', '', $statement);
    
    // Remove backticks
    $statement = str_replace('`', '', $statement);
    
    try {
        $db->exec($statement);
        $successCount++;
        
        if (stripos($statement, 'CREATE TABLE') !== false) {
            $tableCount++;
            preg_match('/CREATE TABLE\s+(\w+)/i', $statement, $matches);
            echo "✓ Created table: " . ($matches[1] ?? 'unknown') . "\n";
        } elseif (stripos($statement, 'INSERT INTO') !== false) {
            $insertCount++;
        }
    } catch (Exception $e) {
        $errorCount++;
        if (stripos($statement, 'CREATE TABLE') !== false || stripos($statement, 'INSERT INTO') !== false) {
            preg_match('/(CREATE TABLE|INSERT INTO)\s+(\w+)/i', $statement, $matches);
            echo "✗ Error with " . ($matches[2] ?? 'statement') . ": " . $e->getMessage() . "\n";
        }
    }
}

$db->exec('COMMIT');
$db->exec('PRAGMA foreign_keys = ON');

echo "\n" . str_repeat("=", 50) . "\n";
echo "Import Summary:\n";
echo "  Tables created: $tableCount\n";
echo "  INSERT statements: $insertCount\n";
echo "  Successful: $successCount\n";
echo "  Errors: $errorCount\n";
echo str_repeat("=", 50) . "\n";

// List all tables
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
$tables = $result->fetchAll(PDO::FETCH_COLUMN);
echo "\nTotal tables in database: " . count($tables) . "\n";
foreach ($tables as $table) {
    $countResult = $db->query("SELECT COUNT(*) FROM $table");
    $count = $countResult->fetchColumn();
    echo "  - $table ($count rows)\n";
}

echo "\n✓ Migration complete! Database file: $dbPath\n";
