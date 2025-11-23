<?php
require 'vendor/autoload.php'; // Load Composer dependencies

// 1. MySQL Connection
$mysql = new mysqli("localhost", "root", "", "guvi");
if ($mysql->connect_error) die("MySQL Connection Failed");

// 2. MongoDB Connection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$mongoDb = $mongoClient->user_system;
$mongoProfiles = $mongoDb->profiles;

// 3. Redis Connection
$redis = new Predis\Client();

// Helper to send JSON response
function sendResponse($success, $message, $data = []) {
    echo json_encode(["success" => $success, "message" => $message, "data" => $data]);
    exit;
}
?>