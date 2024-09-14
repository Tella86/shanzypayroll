<?php
session_start();
include('../db.php'); // Database connection

// Ensure the user is logged in as an admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch all attendance records
$query = "SELECT a.*, e.name AS employee_name FROM attendance a 
          JOIN employees e ON a.employee_id = e.id 
          ORDER BY a.date DESC";
$attendance_records = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
<?php include '../leave/header.php';?>

<div class="container">
    <h2>Attendance Records</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($attendance = mysqli_fetch_assoc($attendance_records)) { ?>
                <tr>
                    <td><?= $attendance['employee_name'] ?></td>
                    <td><?= date('d-M-Y', strtotime($attendance['date'])) ?></td>
                    <td><?= $attendance['check_in'] ? date('H:i:s', strtotime($attendance['check_in'])) : '-' ?></td>
                    <td><?= $attendance['check_out'] ? date('H:i:s', strtotime($attendance['check_out'])) : '-' ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
