<?php
$servername = "localhost";
$username = "root";
$password = "Elon2508/*-";
$database = "payroll_system";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
