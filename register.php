<?php
require 'db_connect.php';
$username = $_POST['username'];
$password = $_POST['password'];

// Validate inputs as needed...

// Hash the password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
if ($stmt->execute([$username, $passwordHash])) {
    echo 'Registration successful. <a href="login.html">Click here to login</a>.';
} else {
    echo 'An error occurred during registration.';
}
?>
