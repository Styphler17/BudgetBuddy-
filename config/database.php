<?php
/**
 * Database Configuration and Connection Class
 */

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Using the Hostinger credentials provided by the user
        $host = "localhost";
        $db   = "u509059322_budgetbuddy202";
        $user = "root";
        $pass = "root";
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        try {
             $this->conn = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
             // In production, log this and show a generic error
             die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
