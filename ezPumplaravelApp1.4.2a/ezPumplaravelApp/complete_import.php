<?php
/**
 * Complete MySQL to SQLite import - handles all tables and data
 */

set_time_limit(0);
ini_set('memory_limit', '2G');

$mysqlDumpFile = 'C:\Users\TalhaElahi\Downloads\DBs dump\id21dump170926.sql\id21dump170926.sql';
$dbPath = __DIR__ . '/database/database.sqlite';

echo "=== COMPLETE DATABASE IMPORT ===\n\n";
echo "Step 1: Reading MySQL dump...\n";

if (!file_exists($mysqlDumpFile)) {
    die("ERROR: MySQL dump file not found at: $mysqlDumpFile\n");
}

$content = file_get_contents($mysqlDumpFile);
echo "✓ Read " . strlen($content) . " bytes\n\n";

echo "Step 2: Cleaning up old database...\n";
if (file_exists($dbPath)) {
    unlink($dbPath);
    echo "✓ Deleted old database\n";
}
touch($dbPath);
echo "✓ Created fresh database\n\n";

echo "Step 3: Parsing SQL dump...\n";

// Split by statement but preserve INSERT INTO statements properly
$lines = explode("\n", $content);
$statements = [];
$currentStatement = '';
$inInsert = false;

foreach ($lines as $line) {
    $trimmed = trim($line);
    
    // Skip comments and MySQL-specific commands
    if (empty($trimmed) || 
        preg_match('/^--/', $trimmed) || 
        preg_match('/^\/\*!/', $trimmed) ||
        preg_match('/^SET /', $trimmed) ||
        preg_match('/^LOCK TABLES/', $trimmed) ||
        preg_match('/^UNLOCK TABLES/', $trimmed) ||
        preg_match('/^\/\*!40000 ALTER TABLE/', $trimmed)) {
        continue;
    }
    
    // Start of INSERT
    if (preg_match('/^INSERT INTO/i', $trimmed)) {
        $inInsert = true;
        $currentStatement = $trimmed;
        continue;
    }
    
    // Inside INSERT or other statement
    if ($inInsert || !empty($currentStatement)) {
        $currentStatement .= "\n" . $line;
        
        // Check if statement ends
        if (preg_match('/;\s*$/', $trimmed)) {
            $statements[] = $currentStatement;
            $currentStatement = '';
            $inInsert = false;
        }
    } else {
        $currentStatement = $trimmed;
    }
}

// Add last statement if exists
if (!empty($currentStatement)) {
    $statements[] = $currentStatement;
}

echo "✓ Found " . count($statements) . " SQL statements\n\n";

echo "Step 4: Connecting to SQLite...\n";
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec('PRAGMA foreign_keys = OFF');
$db->exec('PRAGMA synchronous = OFF');
$db->exec('PRAGMA journal_mode = MEMORY');
echo "✓ Connected\n\n";

echo "Step 5: Processing statements...\n";
$db->exec('BEGIN TRANSACTION');

$stats = [
    'tables_created' => 0,
    'tables_failed' => 0,
    'inserts_success' => 0,
    'inserts_failed' => 0,
    'failed_tables' => []
];

$currentTable = '';

foreach ($statements as $idx => $statement) {
    $statement = trim($statement);
    if (empty($statement)) continue;
    
    $isCreate = preg_match('/^CREATE TABLE/i', $statement);
    $isInsert = preg_match('/^INSERT INTO/i', $statement);
    $isDrop = preg_match('/^DROP TABLE/i', $statement);
    
    // Skip DROP statements
    if ($isDrop) continue;
    
    // Extract table name
    if (preg_match('/(CREATE TABLE|INSERT INTO)\s+`?(\w+)`?/i', $statement, $m)) {
        $currentTable = $m[2];
    }
    
    if ($isCreate) {
        // Remove backticks
        $statement = str_replace('`', '', $statement);
        
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
        $statement = preg_replace('/\benum\s*\([^)]+\)/i', 'TEXT', $statement);
        
        // Remove attributes
        $statement = preg_replace('/\bAUTO_INCREMENT\b/i', '', $statement);
        $statement = preg_replace('/\bUNSIGNED\b/i', '', $statement);
        $statement = preg_replace('/\bZEROFILL\b/i', '', $statement);
        $statement = preg_replace('/\bON UPDATE CURRENT_TIMESTAMP\b/i', '', $statement);
        $statement = preg_replace('/\bCHARACTER SET \w+/i', '', $statement);
        $statement = preg_replace('/\bCOLLATE \w+/i', '', $statement);
        
        // Remove COMMENT
        $statement = preg_replace('/\s+COMMENT\s+[\'"][^\'"]*[\'"]/i', '', $statement);
        
        // Remove KEY definitions - but keep PRIMARY KEY
        $statement = preg_replace('/,\s*UNIQUE KEY\s+\w+\s*\([^)]+\)/i', '', $statement);
        $statement = preg_replace('/,\s*KEY\s+\w+\s*\([^)]+\)/i', '', $statement);
        $statement = preg_replace('/,\s*INDEX\s+\w+\s*\([^)]+\)/i', '', $statement);
        $statement = preg_replace('/,\s*CONSTRAINT\s+\w+\s+FOREIGN KEY\s*\([^)]*\)[^,)]*/i', '', $statement);
        
        // Remove table options
        $statement = preg_replace('/\)\s*ENGINE\s*=\s*\w+[^;]*/i', ')', $statement);
        
        // Clean up extra commas
        $statement = preg_replace('/,(\s*\))/', '$1', $statement);
        
        try {
            $db->exec($statement);
            $stats['tables_created']++;
            echo "✓ Table: $currentTable\n";
        } catch (Exception $e) {
            $stats['tables_failed']++;
            $stats['failed_tables'][] = $currentTable;
            echo "✗ Table: $currentTable - " . $e->getMessage() . "\n";
        }
    }
    elseif ($isInsert) {
        // Remove backticks from INSERT
        $statement = str_replace('`', '', $statement);
        
        try {
            $db->exec($statement);
            $stats['inserts_success']++;
            
            // Show progress every 1000 inserts
            if ($stats['inserts_success'] % 1000 == 0) {
                echo "  Progress: {$stats['inserts_success']} rows inserted...\r";
            }
        } catch (Exception $e) {
            $stats['inserts_failed']++;
            
            // Only show first few errors per table
            if ($stats['inserts_failed'] < 3) {
                echo "✗ Insert into $currentTable failed: " . $e->getMessage() . "\n";
            }
        }
    }
}

$db->exec('COMMIT');
$db->exec('PRAGMA foreign_keys = ON');

echo "\n\n" . str_repeat("=", 70) . "\n";
echo "IMPORT COMPLETE\n";
echo str_repeat("=", 70) . "\n";
echo "Tables created: " . $stats['tables_created'] . "\n";
echo "Tables failed: " . $stats['tables_failed'] . "\n";
echo "Rows inserted: " . $stats['inserts_success'] . "\n";
echo "Inserts failed: " . $stats['inserts_failed'] . "\n";
echo str_repeat("=", 70) . "\n\n";

if (!empty($stats['failed_tables'])) {
    echo "Failed tables: " . implode(', ', $stats['failed_tables']) . "\n\n";
}

echo "Step 6: Verifying database...\n";
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
$tables = $result->fetchAll(PDO::FETCH_COLUMN);

echo "\nDatabase contains " . count($tables) . " tables:\n";
echo str_repeat("-", 70) . "\n";
printf("%-40s %15s\n", "Table Name", "Row Count");
echo str_repeat("-", 70) . "\n";

$totalRows = 0;
foreach ($tables as $table) {
    try {
        $count = $db->query("SELECT COUNT(*) FROM \"$table\"")->fetchColumn();
        $totalRows += $count;
        printf("%-40s %15s\n", $table, number_format($count));
    } catch (Exception $e) {
        printf("%-40s %15s\n", $table, "Error");
    }
}

echo str_repeat("-", 70) . "\n";
printf("%-40s %15s\n", "TOTAL", number_format($totalRows));
echo str_repeat("-", 70) . "\n";

echo "\n✓ Database file: $dbPath\n";
echo "✓ Total size: " . number_format(filesize($dbPath)) . " bytes\n";
