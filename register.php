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
    echo json_encode(['status' => 'error', 'message' => 'Please fill in both fields.']);
    exit;
}

// Check if the username already exists using a prepared statement.
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode(['status' => 'error', 'message' => 'Username already exists.']);
    exit;
}

// Hash the password securely.
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user using a prepared statement to prevent SQL injection.
$stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
if ($stmt->execute([$username, $password_hash])) {
    echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Registration failed, please try again.']);
}
?>
