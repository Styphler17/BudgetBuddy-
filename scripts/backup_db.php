<?php
/**
 * Database Backup Script
 * Generates a SQL dump of the database.
 */

require_once dirname(__DIR__) . '/config/database.php';

// Only allow execution from CLI or via a secret key if accessed via web
$secret_key = "spendscribe_backup_secret_123"; // In production, move to .env
if (php_sapi_name() !== 'cli' && ($_GET['key'] ?? '') !== $secret_key) {
    die("Unauthorized access.");
}

try {
    $conn = Database::getConnection();
    $db_name = "u509059322_budgetbuddy202"; // This should ideally be fetched from config or .env
    
    $backup_dir = dirname(__DIR__) . '/backups';
    if (!is_dir($backup_dir)) {
        mkdir($backup_dir, 0755, true);
    }

    $filename = $backup_dir . '/backup_' . date('Y-m-d_H-i-s') . '.sql';
    
    // Simplistic backup implementation using PDO
    // In a real environment with shell access, mysqldump is preferred
    $tables = [];
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $sql = "-- SpendScribe Database Backup\n";
    $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";

    foreach ($tables as $table) {
        $result = $conn->query("SELECT * FROM $table");
        $num_fields = $result->columnCount();

        $sql .= "DROP TABLE IF EXISTS `$table`;\n";
        $row2 = $conn->query("SHOW CREATE TABLE $table")->fetch(PDO::FETCH_NUM);
        $sql .= $row2[1] . ";\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $sql .= "INSERT INTO `$table` VALUES(";
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $sql .= '"' . $row[$j] . '"';
                    } else {
                        $sql .= 'NULL';
                    }
                    if ($j < ($num_fields - 1)) {
                        $sql .= ',';
                    }
                }
                $sql .= ");\n";
            }
        }
        $sql .= "\n\n\n";
    }

    file_put_contents($filename, $sql);
    echo "Backup successfully created: " . basename($filename) . "\n";

} catch (Exception $e) {
    echo "Backup failed: " . $e->getMessage() . "\n";
}
