<?php
// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=payroll_system', 'root', 'Elon2508/*-');

// Fetch all available themes from the database
$stmt = $pdo->query("SELECT * FROM themes");
$themes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the themes in JSON format
echo json_encode($themes);
?>
