<?php
/**
 * SpendScribe - PHP MVC Entry Point
 */

// Define Environment Mode
define('APP_ENV', 'production');

// Error reporting configuration
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Session Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define Path Constants
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Base URL detection
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = rtrim($protocol . $host . $script_name, '/');
define('BASE_URL', $base_url);

// Autoloader - Controllers, Models, and Router
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/router/',
        APP_PATH . '/services/',
        APP_PATH . '/helpers/'
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
