<?php
session_start();
include('db.php'); // Database connection

// Ensure the user is logged in as a staff member
if ($_SESSION['role'] != 'staff') {
    header('Location: login.php');
    exit();
}

// Get the current employee ID from the session
$employee_id = $_SESSION['user_id'];

// Check if the employee has already checked in today
$date_today = date('Y-m-d');
$query = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND date = '$date_today'";
$result = mysqli_query($conn, $query);
$attendance = mysqli_fetch_assoc($result);

// Handle check-in and check-out
if (isset($_POST['check_in'])) {
    if (!$attendance) {
        // Employee is checking in for the first time today
        $check_in_time = date('Y-m-d H:i:s');
        $insert_query = "INSERT INTO attendance (employee_id, check_in, date) VALUES ('$employee_id', '$check_in_time', '$date_today')";
        if (mysqli_query($conn, $insert_query)) {
            echo "Checked in successfully!";
            header("Refresh:0");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "You have already checked in today.";
    }
}

if (isset($_POST['check_out'])) {
    if ($attendance && !$attendance['check_out']) {
        // Employee is checking out
        $check_out_time = date('Y-m-d H:i:s');
        $update_query = "UPDATE attendance SET check_out = '$check_out_time' WHERE id = '{$attendance['id']}'";
        if (mysqli_query($conn, $update_query)) {
            echo "Checked out successfully!";
            header("Refresh:0");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "You haven't checked in today or you've already checked out.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check In/Out</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Employee Attendance</h2>
    
    <?php if (!$attendance) { ?>
        <!-- Check-in button -->
        <form method="POST" action="check.php">
            <button type="submit" name="check_in" class="btn btn-primary">Check In</button>
        </form>
    <?php } elseif ($attendance && !$attendance['check_out']) { ?>
        <!-- Check-out button -->
        <form method="POST" action="check.php">
            <button type="submit" name="check_out" class="btn btn-warning">Check Out</button>
        </form>
    <?php } else { ?>
        <p>You have already checked in and checked out for today.</p>
    <?php } ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
