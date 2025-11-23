<?php
header('Content-Type: application/json');
require 'db.php';

// validate Token from Header
$headers = getallheaders();
$token = $headers['Authorization'] ?? '';

if (!$token) {
    sendResponse(false, "No token provided");
}

// Check Redis
$userId = $redis->get("session:$token");

if (!$userId) {
    sendResponse(false, "Session expired or invalid");
}

$action = $_POST['action'] ?? 'fetch';

if ($action === 'fetch') {
    // Fetch from MongoDB
    $profile = $mongoProfiles->findOne(['user_id' => (int)$userId]);
    
    // Convert BSON to array
    $data = [
        'age' => $profile['age'] ?? '',
        'dob' => $profile['dob'] ?? '',
        'contact' => $profile['contact'] ?? ''
    ];
    sendResponse(true, "Data fetched", $data);
} 
elseif ($action === 'update') {
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];

    $mongoProfiles->updateOne(
        ['user_id' => (int)$userId],
        ['$set' => ['age' => $age, 'dob' => $dob, 'contact' => $contact]]
    );

    sendResponse(true, "Profile updated successfully");
}
?>