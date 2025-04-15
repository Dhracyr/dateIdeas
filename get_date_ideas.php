<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    // Return an empty array if there is no logged in user.
    echo json_encode([]);
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, title, money, time, energy, timeOfDay FROM ideas WHERE user_id = ?");
$stmt->execute([$userId]);

// Fetch all ideas as an associative array.
$ideas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ensure $ideas is an array (if no records found, it will be an empty array).
$ideas = $ideas ? $ideas : [];

// Encode the ideas as JSON and output them.
echo json_encode($ideas);
?>
