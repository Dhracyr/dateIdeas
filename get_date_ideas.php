<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT title, money, time, energy, timeOfDay FROM ideas WHERE user_id = ?");
$stmt->execute([$userId]);
$ideas = $stmt->fetchAll();

echo json_encode($ideas);
?>
