<?php
/**
 * FINAL Complete MySQL to SQLite import
 */

set_time_limit(0);
ini_set('memory_limit', '2G');

$mysqlDumpFile = 'C:\Users\TalhaElahi\Downloads\DBs dump\id21dump170926.sql\id21dump170926.sql';
$dbPath = __DIR__ . '/database/database.sqlite';

echo "=== COMPLETE DATABASE IMPORT - FINAL VERSION ===\n\n";

// Delete old database
if (file_exists($dbPath)) {
    unlink($dbPath);
}
touch($dbPath);

$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec('PRAGMA foreign_keys = OFF');
$db->exec('PRAGMA synchronous = OFF');
$db->exec('PRAGMA journal_mode = MEMORY');
$db->exec('BEGIN TRANSACTION');

echo "Reading and processing MySQL dump...\n\n";

$handle = fopen($mysqlDumpFile, 'r');
$statement = '';
$inStatement = false;
$stats = ['created' => 0, 'inserted' => 0, 'errors' => 0];
$currentTable = '';

while (($line = fgets($handle)) !== false) {
    $trimmed = trim($line);
    
    // Skip empty lines and comments
    if (empty($trimmed) || 
        preg_match('/^--/', $trimmed) ||
        preg_match('/^\/\*!/', $trimmed)) {
        continue;
    }
    
    // Skip MySQL-specific commands
    if (preg_match('/^(SET|LOCK|UNLOCK|DROP|ALTER TABLE.*KEYS)/', $trimmed)) {
        continue;
    }
    
    // Start collecting statement
    if (preg_match('/^(CREATE TABLE|INSERT INTO)/i', $trimmed)) {
        $statement = $line;
        $inStatement = true;
        continue;
    }
    
    // Continue collecting
    if ($inStatement) {
        $statement .= $line;
        
        // Statement complete?
        if (preg_match('/;\s*$/', $trimmed)) {
            $isCreate = stripos($statement, 'CREATE TABLE') !== false;
            $isInsert = stripos($statement, 'INSERT INTO') !== false;
            
            // Extract table name
            if (preg_match('/(CREATE TABLE|INSERT INTO)\s+`?(\w+)`?/i', $statement, $m)) {
                $currentTable = $m[2];
            }
            
            // Convert MySQL to SQLite
            $statement = str_replace('`', '', $statement);
            
            if ($isCreate) {
                // Data types
                $statement = preg_replace('/\btinyint\(\d+\)/i', 'INTEGER', $statement);
                $statement = preg_replace('/\bsmallint\(\d+\)/i', 'INTEGER', $statement);
                $statement = preg_replace('/\bmediumint\(\d+\)/i', 'INTEGER', $statement);
                $statement = preg_replace('/\bint\(\d+\)/i', 'INTEGER', $statement);
                $statement = preg_replace('/\bbigint\(\d+\)/i', 'INTEGER', $statement);
                $statement = preg_replace('/\bfloat\(\d+(,\d+)?\)/i', 'REAL', $statement);
                $statement = preg_replace('/\bdouble\(\d+(,\d+)?\)/i', 'REAL', $statement);
                $statement = preg_replace('/\bdecimal\(\d+,\d+\)/i', 'REAL', $statement);
                $statement = preg_replace('/\bvarchar\(\d+\)/i', 'TEXT', $statement);
                $statement = preg_replace('/\bchar\(\d+\)/i', 'TEXT', $statement);
                $statement = preg_replace('/\b(long|medium|tiny)?text\b/i', 'TEXT', $statement);
                $statement = preg_replace('/\b(datetime|timestamp|date|time)\b/i', 'TEXT', $statement);
                $statement = preg_replace('/\benum\s*\([^)]+\)/i', 'TEXT', $statement);
                
                // Attributes
                $statement = preg_replace('/\bAUTO_INCREMENT\b/i', '', $statement);
                $statement = preg_replace('/\bUNSIGNED\b/i', '', $statement);
                $statement = preg_replace('/\bZEROFILL\b/i', '', $statement);
                $statement = preg_replace('/\bON UPDATE CURRENT_TIMESTAMP\b/i', '', $statement);
                $statement = preg_replace('/\bCHARACTER SET \w+/i', '', $statement);
                $statement = preg_replace('/\bCOLLATE \w+/i', '', $statement);
                $statement = preg_replace('/\bCOMMENT\s+[\'"][^\'"]*[\'"]/i', '', $statement);
                
                // Remove keys (except PRIMARY KEY)
                $statement = preg_replace('/,\s*UNIQUE KEY\s+\w+\s*\([^)]+\)/i', '', $statement);
                $statement = preg_replace('/,\s*KEY\s+\w+\s*\([^)]+\)/i', '', $statement);
                $statement = preg_replace('/,\s*INDEX\s+\w+\s*\([^)]+\)/i', '', $statement);
                $statement = preg_replace('/,\s*CONSTRAINT\s+[^,)]+/i', '', $statement);
                
                // Remove table options
                $statement = preg_replace('/\)\s*ENGINE\s*=\s*\w+[^;]*/i', ')', $statement);
                $statement = preg_replace('/,\s*\)/', ')', $statement);
            }
            
            // Execute
            try {
                $db->exec($statement);
                
                if ($isCreate) {
                    $stats['created']++;
                    echo "✓ Created: $currentTable\n";
                } elseif ($isInsert) {
                    $stats['inserted']++;
                    if ($stats['inserted'] % 500 == 0) {
                        echo "  Inserted {$stats['inserted']} rows...\r";
                    }
                }
            } catch (Exception $e) {
                $stats['errors']++;
                if ($isCreate) {
                    echo "✗ Failed: $currentTable - " . $e->getMessage() . "\n";
                }
            }
            
            $statement = '';
            $inStatement = false;
        }
    }
}

fclose($handle);

$db->exec('COMMIT');
$db->exec('PRAGMA foreign_keys = ON');

echo "\n\n" . str_repeat("=", 70) . "\n";
echo "IMPORT SUMMARY\n";
echo str_repeat("=", 70) . "\n";
echo "Tables created: {$stats['created']}\n";
echo "Data rows inserted: {$stats['inserted']}\n";
echo "Errors: {$stats['errors']}\n";
echo str_repeat("=", 70) . "\n\n";

// Verify
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
$tables = $result->fetchAll(PDO::FETCH_COLUMN);

echo "Final database has " . count($tables) . " tables:\n";
echo str_repeat("-", 70) . "\n";
printf("%-40s %15s\n", "Table", "Rows");
echo str_repeat("-", 70) . "\n";

$totalRows = 0;
foreach ($tables as $table) {
    try {
        $count = $db->query("SELECT COUNT(*) FROM \"$table\"")->fetchColumn();
        $totalRows += $count;
        if ($count > 0) {
            printf("%-40s %15s\n", $table, number_format($count));
        }
    } catch (Exception $e) {}
}

echo str_repeat("-", 70) . "\n";
printf("%-40s %15s\n", "TOTAL ROWS", number_format($totalRows));
echo str_repeat("-", 70) . "\n";

echo "\n✓ COMPLETE! Database: $dbPath\n";
