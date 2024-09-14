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
        $insert_query = "INSERT INTO attendance (employee_id, check_in, date, status) VALUES ('$employee_id', '$check_in_time', '$date_today', 'Present')";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>
            alert('Checked in successfully!');
            window.location.href = 'check_in_out.php';
          </script>";
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
        $update_query = "UPDATE attendance SET check_out = '$check_out_time', status = 'Absent' WHERE id = '{$attendance['id']}'";
        if (mysqli_query($conn, $update_query)) {
            echo "<script>
            alert('Checked out successfully!');
            window.location.href = 'check_in_out.php';
          </script>";
            header("Refresh:0");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "You haven't checked in today or you've already checked out.";
    }
}
?>
