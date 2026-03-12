<?php
/**
 * BudgetBuddy - PHP MVC Entry Point
 */

// Error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Session Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define Path Constants
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Autoloader - Controllers, Models, and Router
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/router/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load Database Config
require_once CONFIG_PATH . '/database.php';

// Initialize and execute Router
$router = new Router();
$router->handleRequest();
