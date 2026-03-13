<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getConnection();
    
    // Reset password for changeme@test.com to 'password123'
    $new_password = 'password123';
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $result = $stmt->execute([$hash, 'changeme@test.com']);
    
    if ($result) {
        echo "Password for changeme@test.com has been reset to: $new_password\n";
        echo "New Hash: $hash\n";
    } else {
        echo "Failed to update password.\n";
    }
    
    // Verify it immediately in PHP
    $stmt = $db->prepare("SELECT password_hash FROM users WHERE email = ?");
    $stmt->execute(['changeme@test.com']);
    $db_hash = $stmt->fetchColumn();
    
    echo "Verification check: " . (password_verify($new_password, $db_hash) ? "PASSED" : "FAILED") . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
