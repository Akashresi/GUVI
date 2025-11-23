<?php
header('Content-Type: application/json');
require 'db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $mysql->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        // Generate a random token
        $token = bin2hex(random_bytes(32));
        
        // Store in Redis (Token -> UserID) with 1 hour expiry
        $redis->setex("session:$token", 3600, $row['id']);

        sendResponse(true, "Login successful", ["token" => $token]);
    }
}

sendResponse(false, "Invalid credentials");
?>