<?php
$servername = "localhost";
$username = "root";
$password = "Elon2508/*-";
$database = "sttcsms";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
