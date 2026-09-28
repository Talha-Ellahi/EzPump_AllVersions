<?php
/**
 * Complete MySQL to SQLite converter with better syntax handling
 */

$mysqlDumpFile = 'C:\Users\TalhaElahi\Downloads\DBs dump\id21dump170926.sql\id21dump170926.sql';
$dbPath = __DIR__ . '/database/database.sqlite';

echo "=== MySQL to SQLite Complete Converter ===\n\n";
echo "Reading MySQL dump: $mysqlDumpFile\n";

$sql = file_get_contents($mysqlDumpFile);

echo "Processing SQL statements...\n";

// Remove MySQL-specific comments and commands
$sql = preg_replace('/^\/\*!.*?\*\/;?\s*$/m', '', $sql);
$sql = preg_replace('/^-- .*$/m', '', $sql);
$sql = preg_replace('/^SET .*$/m', '', $sql);
$sql = preg_replace('/^LOCK TABLES.*$/m', '', $sql);
$sql = preg_replace('/^UNLOCK TABLES.*$/m', '', $sql);
$sql = preg_replace('/^\/\*!40000 ALTER TABLE.*KEYS.*\*\/;?$/m', '', $sql);

// Remove backticks
$sql = str_replace('`', '', $sql);

// Split into individual statements
$statements = [];
$buffer = '';
$inString = false;
$stringChar = '';

for ($i = 0; $i < strlen($sql); $i++) {
    $char = $sql[$i];
    
    if (($char === '"' || $char === "'") && ($i === 0 || $sql[$i-1] !== '\\')) {
        if (!$inString) {
            $inString = true;
            $stringChar = $char;
        } elseif ($char === $stringChar) {
            $inString = false;
        }
    }
    
    $buffer .= $char;
    
    if ($char === ';' && !$inString) {
        $stmt = trim($buffer);
        if (!empty($stmt) && $stmt !== ';') {
            $statements[] = $stmt;
        }
        $buffer = '';
    }
}

echo "Found " . count($statements) . " SQL statements\n";
echo "Converting to SQLite syntax...\n\n";

// Connect to SQLite
if (file_exists($dbPath)) {
    unlink($dbPath);
}
touch($dbPath);

$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec('PRAGMA foreign_keys = OFF');
$db->exec('BEGIN TRANSACTION');

$created = 0;
$inserted = 0;
$errors = 0;
$skipped = 0;

foreach ($statements as $statement) {
    // Skip empty or comment-only statements
    if (empty(trim($statement)) || preg_match('/^(--|\/\*)/', trim($statement))) {
        continue;
    }
    
    // Skip DROP TABLE statements - we're starting fresh
    if (preg_match('/^DROP TABLE/i', $statement)) {
        continue;
    }
    
    $original = $statement;
    $isCreate = preg_match('/^CREATE TABLE/i', $statement);
    $isInsert = preg_match('/^INSERT INTO/i', $statement);
    
    // Extract table name for logging
    $tableName = 'unknown';
    if (preg_match('/(CREATE TABLE|INSERT INTO)\s+(\w+)/i', $statement, $m)) {
        $tableName = $m[2];
    }
    
    // Convert data types
    $statement = preg_replace('/\btinyint\(\d+\)/i', 'INTEGER', $statement);
    $statement = preg_replace('/\bsmallint\(\d+\)/i', 'INTEGER', $statement);
    $statement = preg_replace('/\bmediumint\(\d+\)/i', 'INTEGER', $statement);
    $statement = preg_replace('/\bint\(\d+\)/i', 'INTEGER', $statement);
    $statement = preg_replace('/\bbigint\(\d+\)/i', 'INTEGER', $statement);
    $statement = preg_replace('/\bfloat(\(\d+,\d+\))?/i', 'REAL', $statement);
    $statement = preg_replace('/\bdouble(\(\d+,\d+\))?/i', 'REAL', $statement);
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
    $statement = preg_replace('/\benum\([^)]+\)/i', 'TEXT', $statement);
    
    // Remove attributes
    $statement = preg_replace('/\bAUTO_INCREMENT\b/i', '', $statement);
    $statement = preg_replace('/\bUNSIGNED\b/i', '', $statement);
    $statement = preg_replace('/\bZEROFILL\b/i', '', $statement);
    $statement = preg_replace('/\bON UPDATE CURRENT_TIMESTAMP\b/i', '', $statement);
    
    // Remove table options at the end of CREATE TABLE
    if ($isCreate) {
        // Remove everything after the last closing parenthesis for CREATE TABLE
        $statement = preg_replace('/\)\s+(ENGINE|DEFAULT|AUTO_INCREMENT|CHARSET|COLLATE|COMMENT).*$/i', ')', $statement);
        
        // Remove KEY definitions
        $statement = preg_replace('/,\s*KEY\s+\w+\s*\([^)]+\)/i', '', $statement);
        $statement = preg_replace('/,\s*UNIQUE KEY\s+\w+\s*\([^)]+\)/i', '', $statement);
        $statement = preg_replace('/,\s*INDEX\s+\w+\s*\([^)]+\)/i', '', $statement);
        $statement = preg_replace('/,\s*CONSTRAINT\s+\w+\s+FOREIGN KEY[^,)]+/i', '', $statement);
        
        // Remove COMMENT from column definitions
        $statement = preg_replace('/\s+COMMENT\s+\'[^\']*\'/i', '', $statement);
        $statement = preg_replace('/\s+COMMENT\s+"[^"]*"/i', '', $statement);
        
        // Fix multiple commas
        $statement = preg_replace('/,\s*,/', ',', $statement);
        $statement = preg_replace('/,\s*\)/', ')', $statement);
    }
    
    try {
        $db->exec($statement);
        
        if ($isCreate) {
            $created++;
            echo "✓ Created: $tableName\n";
        } elseif ($isInsert) {
            $inserted++;
            if ($inserted % 100 == 0) {
                echo "  Inserted $inserted records...\r";
            }
        }
    } catch (Exception $e) {
        $errors++;
        $errorMsg = $e->getMessage();
        
        // Only show errors for CREATE TABLE and first few INSERT errors per table
        if ($isCreate) {
            echo "✗ Failed to create $tableName\n";
            echo "  Error: $errorMsg\n";
            // echo "  SQL: " . substr($statement, 0, 200) . "...\n\n";
        } elseif ($errors < 5) {
            echo "✗ Failed insert into $tableName: $errorMsg\n";
        }
    }
}

$db->exec('COMMIT');
$db->exec('PRAGMA foreign_keys = ON');

echo "\n\n" . str_repeat("=", 60) . "\n";
echo "IMPORT COMPLETE\n";
echo str_repeat("=", 60) . "\n";
echo "Tables created: $created\n";
echo "Records inserted: $inserted\n";
echo "Errors: $errors\n";
echo str_repeat("=", 60) . "\n\n";

// Show final table list with row counts
echo "Database tables:\n";
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
$tables = $result->fetchAll(PDO::FETCH_COLUMN);

foreach ($tables as $table) {
    try {
        $count = $db->query("SELECT COUNT(*) FROM \"$table\"")->fetchColumn();
        echo sprintf("  %-40s %8d rows\n", $table, $count);
    } catch (Exception $e) {
        echo sprintf("  %-40s %s\n", $table, "Error counting");
    }
}

echo "\n✓ Database ready at: $dbPath\n";
