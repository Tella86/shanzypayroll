<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "Elon2508/*-";
$dbname = "payroll_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id = 1; // Assuming logged-in employee ID is 1

$sql = "SELECT * FROM leaves WHERE employee_id='$employee_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered'><tr><th>Leave Type</th><th>Start Date</th><th>End Date</th><th>Status</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["leave_type"]."</td><td>".$row["start_date"]."</td><td>".$row["end_date"]."</td><td>".$row["status"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "No leave records found.";
}

$conn->close();
?>
