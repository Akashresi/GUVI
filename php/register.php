<?php
header('Content-Type: application/json');
require '../db.php'; // Go up one level to root

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    sendResponse(false, "All fields are required");
}

// Check MySQL for existing user
$stmt = $mysql->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    sendResponse(false, "Username already taken");
}
$stmt->close();

// Insert into MySQL
$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $mysql->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hash);

if ($stmt->execute()) {
    $userId = $stmt->insert_id;

    // Create empty profile in MongoDB
    $mongoProfiles->insertOne([
        'user_id' => (int)$userId,
        'age' => '',
        'dob' => '',
        'contact' => ''
    ]);

    sendResponse(true, "Registration successful");
} else {
    sendResponse(false, "Registration failed");
}
?>