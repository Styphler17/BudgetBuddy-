<?php
/**
 * Database Configuration and Connection Class
 */

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Load .env file if it exists
        $this->loadEnv(dirname(__DIR__) . '/.env');

        // Database credentials from environment variables or defaults
        $host = getenv('DB_HOST') ?: "localhost";
        $db   = getenv('DB_NAME') ?: "u509059322_SpendScribe202";
        $user = getenv('DB_USER') ?: "root";
        $pass = getenv('DB_PASS') ?: "root";
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
             error_log("Database connection failed: " . $e->getMessage());
             die("We are experiencing technical difficulties. Please try again later.");
        }
    }

    private function loadEnv($path) {
        if (!file_exists($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($name, $value) = explode('=', $line, 2);
            putenv(sprintf('%s=%s', trim($name), trim($value)));
        }
    }

    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
