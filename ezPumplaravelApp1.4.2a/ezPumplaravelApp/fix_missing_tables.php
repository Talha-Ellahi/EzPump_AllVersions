<?php
/**
 * Fix all missing tables by extracting them from dump and manually creating
 */

$mysqlDumpFile = 'C:\Users\TalhaElahi\Downloads\DBs dump\id21dump170926.sql\id21dump170926.sql';
$dbPath = __DIR__ . '/database/database.sqlite';

$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$missingTables = [
    'PRODUCT',
    'SysParams', 
    'dip_chart_values',
    'failed_jobs',
    'image_distribution',
    'images',
    'rateslog',
    'tank_alarm_history',
    'tank_alarm_logs',
    'tank_shift_logs',
    'tank_shifts',
    'tank_stock',
    'tank_stock_ledger'
];

echo "Fixing " . count($missingTables) . " missing tables...\n\n";

$content = file_get_contents($mysqlDumpFile);

foreach ($missingTables as $table) {
    echo "Processing: $table\n";
    
    // Extract CREATE TABLE statement
    $pattern = "/CREATE TABLE `$table`\s*\((.*?)\)\s*ENGINE/s";
    if (preg_match($pattern, $content, $match)) {
        $createDef = $match[1];
        
        // Build SQLite CREATE TABLE
        $lines = explode("\n", $createDef);
        $columns = [];
        $primaryKey = '';
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Skip KEY, INDEX, CONSTRAINT, FOREIGN KEY
            if (preg_match('/^(KEY|UNIQUE KEY|INDEX|CONSTRAINT|FOREIGN KEY)/i', $line)) {
                continue;
            }
            
            // Extract PRIMARY KEY
            if (preg_match('/PRIMARY KEY\s*\(`?(\w+)`?\)/i', $line, $m)) {
                $primaryKey = $m[1];
                continue;
            }
            
            // Remove backticks and trailing comma
            $line = str_replace('`', '', $line);
            $line = rtrim($line, ',');
            
            // Convert column definition
            $line = preg_replace('/\btinyint\(\d+\)/i', 'INTEGER', $line);
            $line = preg_replace('/\bsmallint\(\d+\)/i', 'INTEGER', $line);
            $line = preg_replace('/\bmediumint\(\d+\)/i', 'INTEGER', $line);
            $line = preg_replace('/\bint\(\d+\)/i', 'INTEGER', $line);
            $line = preg_replace('/\bbigint\(\d+\)/i', 'INTEGER', $line);
            $line = preg_replace('/\bfloat\(\d+(,\d+)?\)/i', 'REAL', $line);
            $line = preg_replace('/\bdouble\(\d+(,\d+)?\)/i', 'REAL', $line);
            $line = preg_replace('/\bdecimal\(\d+,\d+\)/i', 'REAL', $line);
            $line = preg_replace('/\bvarchar\(\d+\)/i', 'TEXT', $line);
            $line = preg_replace('/\bchar\(\d+\)/i', 'TEXT', $line);
            $line = preg_replace('/\b(long|medium|tiny)?text\b/i', 'TEXT', $line);
            $line = preg_replace('/\b(datetime|timestamp|date|time)\b/i', 'TEXT', $line);
            $line = preg_replace('/\benum\s*\([^)]+\)/i', 'TEXT', $line);
            
            // Remove problematic parts
            $line = preg_replace('/\bAUTO_INCREMENT\b/i', '', $line);
            $line = preg_replace('/\bUNSIGNED\b/i', '', $line);
            $line = preg_replace('/\bZEROFILL\b/i', '', $line);
            $line = preg_replace('/\bCHARACTER SET \w+/i', '', $line);
            $line = preg_replace('/\bCOLLATE \w+/i', '', $line);
            $line = preg_replace('/\bCOMMENT\s+[\'"][^\'"]*[\'"]/i', '', $line);
            
            // Replace DEFAULT functions with static defaults
            $line = preg_replace('/DEFAULT\s+current_timestamp\(\)/i', "DEFAULT '2024-01-01 00:00:00'", $line);
            $line = preg_replace('/DEFAULT\s+CURRENT_TIMESTAMP/i', "DEFAULT '2024-01-01 00:00:00'", $line);
            $line = preg_replace('/ON UPDATE current_timestamp\(\)/i', '', $line);
            $line = preg_replace('/ON UPDATE CURRENT_TIMESTAMP/i', '', $line);
            
            // Clean up extra spaces
            $line = preg_replace('/\s+/', ' ', $line);
            $line = trim($line);
            
            if (!empty($line) && !preg_match('/^\)/', $line)) {
                $columns[] = $line;
            }
        }
        
        // Build final CREATE statement
        $sql = "CREATE TABLE $table (\n  " . implode(",\n  ", $columns);
        if (!empty($primaryKey)) {
            $sql .= ",\n  PRIMARY KEY ($primaryKey)";
        }
        $sql .= "\n)";
        
        // Create table
        try {
            $db->exec($sql);
            echo "  ✓ Created: $table\n";
            
            // Now insert data
            $insertPattern = "/INSERT INTO `$table`\s+VALUES\s*(.*?);/s";
            if (preg_match($insertPattern, $content, $insertMatch)) {
                $insertData = $insertMatch[1];
                $insertSql = "INSERT INTO $table VALUES " . str_replace('`', '', $insertData);
                
                try {
                    $db->exec($insertSql);
                    echo "  ✓ Inserted data\n";
                } catch (Exception $e) {
                    echo "  ✗ Insert failed: " . $e->getMessage() . "\n";
                }
            }
            
        } catch (Exception $e) {
            echo "  ✗ Failed: " . $e->getMessage() . "\n";
            // echo "  SQL: $sql\n\n";
        }
    } else {
        echo "  ✗ Table definition not found in dump\n";
    }
    
    echo "\n";
}

// Verify final count
$result = $db->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table'");
$tableCount = $result->fetchColumn();

echo str_repeat("=", 70) . "\n";
echo "FINAL DATABASE STATUS\n";
echo str_repeat("=", 70) . "\n";
echo "Total tables: $tableCount\n";

$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name IN ('" . implode("','", $missingTables) . "')");
$fixed = $result->fetchAll(PDO::FETCH_COLUMN);

echo "Fixed tables: " . count($fixed) . " / " . count($missingTables) . "\n";
if (!empty($fixed)) {
    echo "  - " . implode("\n  - ", $fixed) . "\n";
}

$missing = array_diff($missingTables, $fixed);
if (!empty($missing)) {
    echo "Still missing: " . count($missing) . "\n";
    echo "  - " . implode("\n  - ", $missing) . "\n";
}

echo "\n✓ Done!\n";
