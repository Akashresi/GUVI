 <?php
require 'vendor/autoload.php'; // Load libraries

echo "<h2>System Status Check</h2>";

// 1. CHECK MYSQL
echo "<strong>MySQL:</strong> ";
try {
    $mysql = new mysqli("localhost", "root", "", "guvi");
    if ($mysql->connect_error) {
        echo "<span style='color:red'>Failed - " . $mysql->connect_error . "</span><br>";
    } else {
        echo "<span style='color:green'>Connected OK</span><br>";
    }
} catch (Exception $e) {
    echo "<span style='color:red'>Error: " . $e->getMessage() . "</span><br>";
}

// 2. CHECK MONGODB
echo "<strong>MongoDB:</strong> ";
try {
    if (!class_exists('MongoDB\Client')) {
        throw new Exception("MongoDB Library not loaded (Check Composer)");
    }
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
    $mongo->listDatabases(); // Try to ping the server
    echo "<span style='color:green'>Connected OK</span><br>";
} catch (Exception $e) {
    echo "<span style='color:red'>Failed - " . $e->getMessage() . "</span><br>";
    echo "<small>Hint: Is the MongoDB Service running? Is extension=mongodb enabled in php.ini?</small><br>";
}

// 3. CHECK REDIS
echo "<strong>Redis:</strong> ";
try {
    if (!class_exists('Predis\Client')) {
        throw new Exception("Predis Library not loaded (Check Composer)");
    }
    $redis = new Predis\Client();
    $redis->connect();
    echo "<span style='color:green'>Connected OK</span><br>";
} catch (Exception $e) {
    echo "<span style='color:red'>Failed - " . $e->getMessage() . "</span><br>";
    echo "<small>Hint: Is Redis Server running?</small><br>";
}
?>