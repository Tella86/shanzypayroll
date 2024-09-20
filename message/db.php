<?php
// Database configuration
$host = 'localhost';  // Database host
$dbname = 'sttcsms';  // Database name
$username = 'root';   // Database username
$password = 'Elon2508/*-'; // Database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
