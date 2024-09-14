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

$sql = "SELECT l.id, e.full_name, l.leave_type, l.start_date, l.end_date, l.reason, l.status 
        FROM leaves l 
        JOIN employees e ON l.employee_id = e.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered'><tr><th>Employee</th><th>Leave Type</th><th>Start Date</th><th>End Date</th><th>Reason</th><th>Status</th><th>Action</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["full_name"]."</td>
                <td>".$row["leave_type"]."</td>
                <td>".$row["start_date"]."</td>
                <td>".$row["end_date"]."</td>
                <td>".$row["reason"]."</td>
                <td>".$row["status"]."</td>
                <td>
                    <form method='POST' action='update_leave_status.php'>
                        <input type='hidden' name='leave_id' value='".$row["id"]."'>
                        <select name='status'>
                            <option value='Approved'>Approve</option>
                            <option value='Rejected'>Reject</option>
                        </select>
                        <button type='submit'>Update</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No leave requests found.";
}

$conn->close();
?>
