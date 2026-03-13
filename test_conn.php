<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h3>Database Connection Tester</h3>";

// Try different common MAMP configurations
$configs = [
    ['host' => 'localhost', 'user' => 'root', 'pass' => 'root', 'name' => 'u509059322_SpendScribe202'],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => 'root', 'name' => 'u509059322_SpendScribe202'],
    ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'u509059322_SpendScribe202'],
];

foreach ($configs as $i => $conf) {
    echo "Testing Config #$i: Host={$conf['host']}, User={$conf['user']}, DB={$conf['name']}... ";
    try {
        $dsn = "mysql:host={$conf['host']};dbname={$conf['name']};charset=utf8mb4";
        $pdo = new PDO($dsn, $conf['user'], $conf['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        echo "<b style='color:green'>SUCCESS!</b><br>";
        
        echo "Updating .env with these working settings... ";
        $content = "DB_HOST={$conf['host']}\nDB_USER={$conf['user']}\nDB_PASS={$conf['pass']}\nDB_NAME={$conf['name']}";
        file_put_contents('.env', $content);
        echo "Done.<br>";
        break;
    } catch (Exception $e) {
        echo "<span style='color:red'>FAILED</span> (" . $e->getMessage() . ")<br>";
    }
}
