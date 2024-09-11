<?php
session_start();
include('db.php'); // Database connection

if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];

    // Delete the employee from the database
    $query = "DELETE FROM employees WHERE id = '$employee_id'";
    if (mysqli_query($conn, $query)) {
        echo "Employee deleted successfully.";
    } else {
        echo "Error deleting employee: " . mysqli_error($conn);
    }

    // Redirect back to view_employees.php
    header('Location: admin_dashboard.php');
}
