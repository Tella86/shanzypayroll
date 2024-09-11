<?php
session_start();
$userId = $_SESSION['user_id']; // Assuming user is logged in

// Fetch the user's saved theme color from the database
$pdo = new PDO('mysql:host=localhost;dbname=payroll_system', 'root', 'Elon2508/*-');
$stmt = $pdo->prepare("SELECT theme_color FROM users WHERE id = ?");
$stmt->execute([$userId]);
$themeColor = $stmt->fetchColumn();

echo json_encode(['themeColor' => $themeColor]);
?>
