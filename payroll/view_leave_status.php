<?php
session_start();
include('db.php'); // Include database connection

// Ensure the user is logged in as a staff member
if ($_SESSION['role'] != 'staff') {
    header('Location: login.php');
    exit();
}

// Get the logged-in employee's leave requests
$employee_id = $_SESSION['user_id'];
$query = "SELECT lr.*, lt.name AS leave_type 
          FROM leave_requests lr
          JOIN leave_types lt ON lr.leave_type_id = lt.id
          WHERE lr.employee_id = '$employee_id'
          ORDER BY lr.request_date DESC";
$leave_requests = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Leave Status</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>My Leave Requests</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Request Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($leave_request = mysqli_fetch_assoc($leave_requests)) { ?>
                <tr>
                    <td><?= $leave_request['leave_type'] ?></td>
                    <td><?= date('d-M-Y', strtotime($leave_request['start_date'])) ?></td>
                    <td><?= date('d-M-Y', strtotime($leave_request['end_date'])) ?></td>
                    <td><?= $leave_request['reason'] ?></td>
                    <td><?= ucfirst($leave_request['status']) ?></td>
                    <td><?= date('d-M-Y H:i:s', strtotime($leave_request['request_date'])) ?></td>
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
