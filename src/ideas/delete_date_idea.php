<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['idea_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Idea ID not provided.']);
    exit;
}

$ideaId = $data['idea_id'];

// Delete the idea ensuring it belongs to the logged-in user
$stmt = $pdo->prepare("DELETE FROM ideas WHERE id = ? AND user_id = ?");
if ($stmt->execute([$ideaId, $userId])) {
    echo json_encode(['status' => 'success', 'message' => 'Idea deleted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete idea.']);
}
?>
