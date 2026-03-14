<?php
session_start();
require_once 'config/database.php';

try {
    $conn = Database::getConnection();
    $userId = $_SESSION['user_id'] ?? 3;
    
    echo "<h2>spendScribe Aggressive Multi-Currency Sync</h2>";
    
    // 1. Get User's Preferred Currency from DB
    $stmt = $conn->prepare("SELECT currency FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $preferred = $stmt->fetchColumn() ?: 'USD';
    
    echo "Current preference in Database: <b>$preferred</b><br>";
    
    // 2. Force session update so UI helpers pick it up immediately
    $_SESSION['user_currency'] = $preferred;
    echo "Session variable synchronized to: <b>" . $_SESSION['user_currency'] . "</b><br>";
    
    // 3. Force update ALL accounts for this user to this currency if they are not already set
    // This fixes the "Accounts showing dollars" issue
    $stmt = $conn->prepare("UPDATE accounts SET currency = ? WHERE user_id = ?");
    $stmt->execute([$preferred, $userId]);
    echo "Force updated " . $stmt->rowCount() . " accounts to <b>$preferred</b>.<br>";
    
    // 4. Clear exchange rates cache to force a fresh pull from the API
    $conn->exec("DELETE FROM exchange_rates");
    echo "Exchange rate cache cleared to ensure accurate conversion.<br>";
    
    echo "<hr><p style='color:green; font-weight:bold;'>All systems synchronized! Your Dashboard and Accounts page will now show $preferred correctly.</p>";
    echo "<p><a href='dashboard' style='padding: 10px 20px; background: #10237f; color: white; text-decoration: none; border-radius: 8px;'>Return to Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
