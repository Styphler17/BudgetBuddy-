<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getConnection();
    
    echo "<h3>Database Schema Repair</h3>";
    echo "Detecting and adding missing columns...<br>";

    // Helper function to check if column exists
    function addColumnIfNeeded($db, $table, $column, $definition) {
        $stmt = $db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        if ($stmt->rowCount() == 0) {
            echo "Adding column '$column' to '$table'... ";
            $db->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
            echo "<b style='color:green'>DONE</b><br>";
        } else {
            echo "Column '$column' already exists in '$table'.<br>";
        }
    }

    // Update transactions table
    addColumnIfNeeded($db, 'transactions', 'account_id', "INT NULL AFTER category_id");
    addColumnIfNeeded($db, 'transactions', 'is_transfer', "TINYINT(1) DEFAULT 0 AFTER type");
    addColumnIfNeeded($db, 'transactions', 'transfer_id', "VARCHAR(50) NULL AFTER is_transfer");

    // Create recurring_transactions table
    echo "Ensuring recurring_transactions table exists... ";
    $db->exec("CREATE TABLE IF NOT EXISTS recurring_transactions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        account_id INT NOT NULL,
        category_id INT NULL,
        amount DECIMAL(10,2) NOT NULL,
        description TEXT,
        type ENUM('income', 'expense') NOT NULL,
        frequency ENUM('daily', 'weekly', 'monthly', 'yearly') NOT NULL,
        start_date DATE NOT NULL,
        last_run_date DATE NULL,
        next_run_date DATE NOT NULL,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (user_id),
        INDEX (account_id)
    ) ENGINE=InnoDB");
    echo "<b style='color:green'>SUCCESS!</b><br>";
    
    echo "<br><a href='dashboard'>Go to Dashboard</a>";
} catch (Exception $e) {
    echo "<b style='color:red'>Error:</b> " . $e->getMessage() . "<br>";
}
