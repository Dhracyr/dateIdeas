<?php
session_start();
require 'db_connect.php';  // Your database connection file

// Only proceed if the request method is POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Option 1: Redirect to login page.
    header("Location: login.html");
    exit;
}

// Check if both username and password are provided.
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Username and password are required.']);
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

// Prepare a query to retrieve the user
$stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    header('Location: index.php');
    exit;
} else {
    echo 'Incorrect login details.';
}
?>
