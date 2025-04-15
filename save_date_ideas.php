<?php
session_start();
require 'db_connect.php';

$userId = $_SESSION['user_id'];
$newIdea = json_decode(file_get_contents('php://input'), true);

// Retrieve current ideas
$stmt = $pdo->prepare('SELECT ideas FROM users WHERE id = ?');
$stmt->execute([$userId]);
$row = $stmt->fetch();
$currentIdeas = json_decode($row['ideas'], true);
if (!$currentIdeas) {
    $currentIdeas = [];
}
$currentIdeas[] = $newIdea;

// Update the user record with new ideas
$stmt = $pdo->prepare('UPDATE users SET ideas = ? WHERE id = ?');
if ($stmt->execute([json_encode($currentIdeas), $userId])) {
    echo json_encode(['status' => 'success', 'ideas' => $currentIdeas]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update ideas.']);
}
?>
