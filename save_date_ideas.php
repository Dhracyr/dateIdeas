<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$userId = $_SESSION['user_id'];
$newIdea = json_decode(file_get_contents('php://input'), true);

// Insert the new idea into the ideas table
$stmt = $pdo->prepare("INSERT INTO ideas (user_id, title, money, time, energy, timeOfDay) VALUES (?, ?, ?, ?, ?, ?)");
if ($stmt->execute([
    $userId,
    $newIdea['title'],
    $newIdea['money'],
    $newIdea['time'],
    $newIdea['energy'],
    $newIdea['timeOfDay']
])) {
    echo json_encode(['status' => 'success', 'message' => 'Idea saved successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save idea.']);
}
?>
