<?php
require_once 'config/database.php';

try {
    $conn = Database::getConnection();
    
    echo "<h2>Updating Database Schema</h2>";
    
    $queries = [
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS verification_token VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS two_factor_secret VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS two_factor_enabled TINYINT(1) DEFAULT 0",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS recovery_codes TEXT DEFAULT NULL",
        "ALTER TABLE goals ADD COLUMN IF NOT EXISTS last_milestone INT DEFAULT 0"
    ];
    
    // Note: IF NOT EXISTS isn't standard for ADD COLUMN in some MySQL versions, 
    // so we wrap each in a try-catch for better compatibility.
    foreach ($queries as $sql) {
        // Strip IF NOT EXISTS if not supported, or just catch the error
        $cleanSql = str_replace(" IF NOT EXISTS", "", $sql);
        try {
            $conn->exec($cleanSql);
            echo "<p style='color:green'>✅ Executed: $cleanSql</p>";
        } catch (Exception $e) {
            echo "<p style='color:orange'>ℹ️ Skipped: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<hr><p style='color:blue; font-weight:bold;'>Schema update completed! You can now return to your <a href='settings'>Settings</a>.</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";
}
