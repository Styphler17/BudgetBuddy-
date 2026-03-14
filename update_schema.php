<?php
require_once 'config/database.php';

try {
    $conn = Database::getConnection();
    
    echo "<h2>spendScribe Database Schema Update</h2>";
    echo "<p>Running necessary updates to support new security, currency, and tracking features...</p>";
    
    $queries = [
        // 1. User Table Updates
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS verification_token VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS two_factor_secret VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS two_factor_enabled TINYINT(1) DEFAULT 0",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS recovery_codes TEXT DEFAULT NULL",
        
        // 2. Goal Table Updates
        "ALTER TABLE goals ADD COLUMN IF NOT EXISTS last_milestone INT DEFAULT 0",
        
        // 3. Account Table Updates
        "ALTER TABLE accounts ADD COLUMN IF NOT EXISTS currency VARCHAR(3) DEFAULT 'USD'",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_pic VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE admins ADD COLUMN IF NOT EXISTS profile_pic VARCHAR(255) DEFAULT NULL",
        
        // 4. Create User Logs Table
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
        
        // 5. Create Exchange Rates Table
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
        // Strip IF NOT EXISTS for ADD COLUMN if needed, or handle error
        $cleanSql = str_replace(" IF NOT EXISTS", "", $sql);
        try {
            $conn->exec($cleanSql);
            echo "<p style='color:green'>✅ Success: " . substr($cleanSql, 0, 50) . "...</p>";
        } catch (Exception $e) {
            // Check if error is 'Duplicate column name' (SQLSTATE 42S21)
            if ($e->getCode() == '42S21') {
                echo "<p style='color:orange'>ℹ️ Skipped (Already exists): " . substr($cleanSql, 0, 50) . "...</p>";
            } else {
                echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<hr><p style='color:blue; font-weight:bold;'>All updates processed! You can now use all new features.</p>";
    echo "<p><a href='dashboard' style='padding: 10px 20px; background: #10237f; color: white; text-decoration: none; rounded: 8px;'>Return to Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Connection Error: " . $e->getMessage() . "</p>";
}
