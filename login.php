<?php
session_start();
require 'db_connect.php';  // Your database connection file

$username = $_POST['username'];
$password = $_POST['password'];

// Prepare a query to retrieve the user
$stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    header('Location: index.html');
    exit;
} else {
    echo 'Incorrect login details.';
}
?>
