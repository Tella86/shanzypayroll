<?php
session_start(); // Start session to track user
$userId = $_SESSION['user_id']; // Assuming user is logged in

// Get data from AJAX request
$data = json_decode(file_get_contents('php://input'), true);
$themeColor = $data['themeColor'];

// Update the user's selected theme in the database
$pdo = new PDO('mysql:host=localhost;dbname=payroll_system', 'root', 'Elon2508/*-');
$stmt = $pdo->prepare("UPDATE users SET theme_color = ? WHERE id = ?");
$stmt->execute([$themeColor, $userId]);
?>