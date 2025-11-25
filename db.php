<?php
// db.php - Located in the Root Folder
require __DIR__ . '/vendor/autoload.php'; // __DIR__ ensures correct path to vendor

// 1. MySQL Connection
$mysql = new mysqli("localhost", "root", "", "guvi");
if ($mysql->connect_error) die("MySQL Connection Failed");

// 2. MongoDB Connection
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $mongoDb = $mongoClient->user_system;
    $mongoProfiles = $mongoDb->profiles;
} catch (Exception $e) {
    die("MongoDB Connection Failed: " . $e->getMessage());
}

// 3. Redis Connection
try {
    $redis = new Predis\Client();
    $redis->connect();
} catch (Exception $e) {
    die("Redis Connection Failed: " . $e->getMessage());
}

// Helper function
function sendResponse($success, $message, $data = []) {
    echo json_encode(["success" => $success, "message" => $message, "data" => $data]);
    exit;
}
?>