<?php
require_once 'config/database.php';

try {
    $conn = Database::getConnection();
    
    echo "<h2>Finalizing Database Schema</h2>";
    
    $queries = [
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS verification_token VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS two_factor_secret VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS two_factor_enabled TINYINT(1) DEFAULT 0",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS recovery_codes TEXT DEFAULT NULL",
        "ALTER TABLE goals ADD COLUMN IF NOT EXISTS last_milestone INT DEFAULT 0",
        "CREATE TABLE IF NOT EXISTS user_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            action VARCHAR(255) NOT NULL,
            details TEXT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS exchange_rates (
            id INT AUTO_INCREMENT PRIMARY KEY,
            from_currency VARCHAR(3) NOT NULL,
            to_currency VARCHAR(3) NOT NULL,
            rate DECIMAL(15, 6) NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY (from_currency, to_currency)
        )"
    ];
    
    foreach ($queries as $sql) {
        $cleanSql = str_replace(" IF NOT EXISTS", "", $sql);
        try {
            $conn->exec($cleanSql);
            echo "<p style='color:green'>✅ Executed: $cleanSql</p>";
        } catch (Exception $e) {
            echo "<p style='color:orange'>ℹ️ Skipped: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<hr><p style='color:blue; font-weight:bold;'>Schema update completed! You can now use all new features including Audit Logs and Milestones.</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";
}
