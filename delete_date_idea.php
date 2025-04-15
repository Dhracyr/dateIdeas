<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['index'])) {
    echo json_encode(['status' => 'error', 'message' => 'Idea index not provided.']);
    exit;
}

$index = $data['index'];

// Retrieve the current ideas for the user.
$stmt = $pdo->prepare('SELECT ideas FROM users WHERE id = ?');
$stmt->execute([$userId]);
$row = $stmt->fetch();

$currentIdeas = json_decode($row['ideas'], true);
if (!is_array($currentIdeas)) {
    $currentIdeas = [];
}

// Check if the idea exists at the given index.
if (!isset($currentIdeas[$index])) {
    echo json_encode(['status' => 'error', 'message' => 'Idea not found.']);
    exit;
}

// Remove the idea using the provided index.
array_splice($currentIdeas, $index, 1);

// Update the database with the new ideas.
$stmt = $pdo->prepare('UPDATE users SET ideas = ? WHERE id = ?');
if ($stmt->execute([json_encode($currentIdeas), $userId])) {
    echo json_encode(['status' => 'success', 'ideas' => $currentIdeas]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update ideas.']);
}
?>
