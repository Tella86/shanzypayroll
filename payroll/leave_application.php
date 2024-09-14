<?php
session_start();
include('db.php'); // Include database connection

// Ensure the user is logged in as a staff member
if ($_SESSION['role'] != 'staff') {
    header('Location: login.php');
    exit();
}

// Fetch available leave types for the dropdown
$leave_types = mysqli_query($conn, "SELECT * FROM leave_types");



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Apply for Leave</h2>
    <form method="POST" action="../leave/submit_leave.php">
        <div class="form-group">
            <label for="leave_type">Leave Type</label>
            <select class="form-control" name="leave_type" required>
                <option value="" disabled selected>Select Leave Type</option>
                <?php while ($leave_type = mysqli_fetch_assoc($leave_types)) { ?>
                    <option value="<?= $leave_type['id'] ?>"><?= $leave_type['name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" name="end_date" required>
        </div>
        <div class="form-group">
            <label for="reason">Reason</label>
            <textarea class="form-control" name="reason" required></textarea>
        </div>
        <button type="submit" name="apply_leave" class="btn btn-primary">Submit Leave Request</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
