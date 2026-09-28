<?php
/**
 * MySQL to SQLite Converter
 * This script converts a MySQL dump to SQLite-compatible SQL
 */

$mysqlDumpFile = 'C:\Users\TalhaElahi\Downloads\DBs dump\id21dump170926.sql\id21dump170926.sql';
$sqliteOutputFile = __DIR__ . '/database/converted_sqlite.sql';

echo "Reading MySQL dump file...\n";
$content = file_get_contents($mysqlDumpFile);

if ($content === false) {
    die("Error: Could not read MySQL dump file at: $mysqlDumpFile\n");
}

echo "Converting MySQL syntax to SQLite...\n";

// Remove MySQL-specific comments and commands
$content = preg_replace('/\/\*!.*?\*\/;?/s', '', $content);
$content = preg_replace('/\/\*M!.*?\*\/;?/s', '', $content);
$content = preg_replace('/^--.*$/m', '', $content);
$content = preg_replace('/^SET .*$/m', '', $content);
$content = preg_replace('/^DROP TABLE IF EXISTS/m', 'DROP TABLE IF EXISTS', $content);

// Remove LOCK/UNLOCK TABLES
$content = preg_replace('/^LOCK TABLES.*$/m', '', $content);
$content = preg_replace('/^UNLOCK TABLES.*$/m', '', $content);

// Remove ENGINE and CHARSET declarations
$content = preg_replace('/\) ENGINE=\w+ DEFAULT CHARSET=\w+( COLLATE=\w+)?;/', ');', $content);
$content = preg_replace('/\) ENGINE=\w+ CHARSET=\w+( COLLATE=\w+)?;/', ');', $content);

// Convert MySQL data types to SQLite equivalents
$content = preg_replace('/\btinyint\(\d+\)/i', 'INTEGER', $content);
$content = preg_replace('/\bsmallint\(\d+\)/i', 'INTEGER', $content);
$content = preg_replace('/\bmediumint\(\d+\)/i', 'INTEGER', $content);
$content = preg_replace('/\bint\(\d+\)/i', 'INTEGER', $content);
$content = preg_replace('/\bbigint\(\d+\)/i', 'INTEGER', $content);
$content = preg_replace('/\bfloat\(\d+,\d+\)/i', 'REAL', $content);
$content = preg_replace('/\bdouble\(\d+,\d+\)/i', 'REAL', $content);
$content = preg_replace('/\bdecimal\(\d+,\d+\)/i', 'REAL', $content);
$content = preg_replace('/\bvarchar\(\d+\)/i', 'TEXT', $content);
$content = preg_replace('/\bchar\(\d+\)/i', 'TEXT', $content);
$content = preg_replace('/\btext\b/i', 'TEXT', $content);
$content = preg_replace('/\blongtext\b/i', 'TEXT', $content);
$content = preg_replace('/\bmediumtext\b/i', 'TEXT', $content);
$content = preg_replace('/\btinytext\b/i', 'TEXT', $content);
$content = preg_replace('/\bdatetime\b/i', 'TEXT', $content);
$content = preg_replace('/\btimestamp\b/i', 'TEXT', $content);
$content = preg_replace('/\bdate\b/i', 'TEXT', $content);
$content = preg_replace('/\btime\b/i', 'TEXT', $content);

// Remove AUTO_INCREMENT
$content = preg_replace('/\bAUTO_INCREMENT\b/i', '', $content);

// Remove UNSIGNED and ZEROFILL
$content = preg_replace('/\bUNSIGNED\b/i', '', $content);
$content = preg_replace('/\bZEROFILL\b/i', '', $content);

// Remove backticks (MySQL identifier quotes)
$content = str_replace('`', '', $content);

// Fix COLLATE statements
$content = preg_replace('/\bCOLLATE\s+\w+/i', '', $content);

// Remove character set client settings
$content = preg_replace('/\/\*!40101 SET @saved_cs_client.*?\*\//s', '', $content);
$content = preg_replace('/\/\*!40101 SET character_set_client.*?\*\//s', '', $content);

// Remove ALTER TABLE DISABLE/ENABLE KEYS
$content = preg_replace('/\/\*!40000 ALTER TABLE \w+ DISABLE KEYS \*\/;?/i', '', $content);
$content = preg_replace('/\/\*!40000 ALTER TABLE \w+ ENABLE KEYS \*\/;?/i', '', $content);

// Clean up multiple empty lines
$content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);

// Add SQLite pragma at the beginning
$sqliteHeader = "-- Converted from MySQL to SQLite\n";
$sqliteHeader .= "PRAGMA foreign_keys = OFF;\n";
$sqliteHeader .= "BEGIN TRANSACTION;\n\n";

$sqliteFooter = "\n\nCOMMIT;\n";
$sqliteFooter .= "PRAGMA foreign_keys = ON;\n";

$content = $sqliteHeader . $content . $sqliteFooter;

echo "Writing SQLite-compatible file...\n";
file_put_contents($sqliteOutputFile, $content);

echo "Conversion complete!\n";
echo "Output file: $sqliteOutputFile\n";
echo "\nNow importing into SQLite database...\n";

// Import into SQLite
$dbPath = __DIR__ . '/database/database.sqlite';
$db = new SQLite3($dbPath);

// Execute the converted SQL
try {
    $db->exec($content);
    echo "✓ Successfully imported data into SQLite database!\n";
    echo "Database location: $dbPath\n";
    
    // Show table count
    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
    $tables = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $tables[] = $row['name'];
    }
    echo "\nImported " . count($tables) . " tables:\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error importing data: " . $e->getMessage() . "\n";
    echo "You can manually import using: sqlite3 database/database.sqlite < database/converted_sqlite.sql\n";
}

$db->close();
echo "\n✓ All done! Your Laravel app should now work with SQLite.\n";
