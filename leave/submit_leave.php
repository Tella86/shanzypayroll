<?php
session_start();
include('../payroll/db.php'); // Include database connection

// Ensure the user is logged in as a staff member
if ($_SESSION['role'] != 'staff') {
    header('Location: ../payroll/login.php');
    exit();
}

// Handle leave request submission
if (isset($_POST['apply_leave'])) {
    $employee_id = $_SESSION['user_id'];  // Assuming employee ID is stored in session
    $leave_type_id = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    // Insert the leave request into the database
    $query = "INSERT INTO leave_requests (employee_id, leave_type_id, start_date, end_date, reason)
              VALUES ('$employee_id', '$leave_type_id', '$start_date', '$end_date', '$reason')";
    
    if (mysqli_query($conn, $query)) {
        // Create a notification for the admin
        $message = "New leave request submitted by Employee ID: $employee_id";
        $notif_query = "INSERT INTO notifications (user_id, message) VALUES ('$employee_id', '$message')";
        mysqli_query($conn, $notif_query);

        echo "<script>
                alert('Leave request submitted successfully!');
                window.location.href = '../payroll/view_leave_status.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
