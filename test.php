<?php
require 'db_connect.php';
$stmt = $pdo->query('SELECT DATABASE()');
echo 'Connected to database: ' . $stmt->fetchColumn();
?>
