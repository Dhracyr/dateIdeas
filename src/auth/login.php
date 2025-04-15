<?php
session_start();
require 'db_connect.php';

// Set header to return JSON.
header('Content-Type: application/json');

// Only process POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// Retrieve and sanitize inputs.
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password');

// Validate input.
if (empty($username) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Please provide both username and password.']);
    exit;
}

// Look up the user using a prepared statement.
$stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    echo json_encode(['status' => 'success', 'message' => 'Login successful.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Incorrect login details.']);
}
?>
