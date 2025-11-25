<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #222; color: #fff; }
        .box { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background-color: #198754; border: 1px solid #146c43; }
        .error { background-color: #dc3545; border: 1px solid #b02a37; }
        h3 { margin-top: 0; }
    </style>
</head>
<body>
    <h1>System Health Check</h1>

<?php
// Enable error reporting to see hidden issues
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 0. Check Composer Dependencies
echo "<h3>0. Checking Dependencies...</h3>";
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
    echo "<div class='box success'>✅ 'vendor/autoload.php' found. Composer is installed.</div>";
} else {
    echo "<div class='box error'>❌ 'vendor/autoload.php' NOT found.<br>Run <code>composer install</code> in your terminal.</div>";
    exit; // Stop here if no libraries
}

// 1. Check MySQL
echo "<h3>1. Checking MySQL...</h3>";
try {
    $mysql = new mysqli("localhost", "root", "", "guvi");
    if ($mysql->connect_error) {
        throw new Exception($mysql->connect_error);
    }
    echo "<div class='box success'>✅ MySQL Connected Successfully.</div>";
} catch (Exception $e) {
    echo "<div class='box error'>❌ MySQL Error: " . $e->getMessage() . "</div>";
}

// 2. Check MongoDB
echo "<h3>2. Checking MongoDB...</h3>";
try {
    if (!class_exists('MongoDB\Client')) {
        throw new Exception("MongoDB Class not found. Is the extension enabled in php.ini?");
    }
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    // We must try to list databases to actually force a connection check
    $dbs = $mongoClient->listDatabases(); 
    echo "<div class='box success'>✅ MongoDB Connected Successfully.</div>";
} catch (Exception $e) {
    echo "<div class='box error'>❌ MongoDB Error: " . $e->getMessage() . "</div>";
}

// 3. Check Redis
echo "<h3>3. Checking Redis...</h3>";
try {
    if (!class_exists('Predis\Client')) {
        throw new Exception("Predis Class not found.");
    }
    $redis = new Predis\Client();
    $redis->connect();
    echo "<div class='box success'>✅ Redis Connected Successfully.</div>";
} catch (Exception $e) {
    echo "<div class='box error'>❌ Redis Error: " . $e->getMessage() . "<br>Make sure Redis server is running.</div>";
}
?>

</body>
</html>