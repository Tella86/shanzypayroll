<?php
session_start();
include('db.php'); // Database connection

// Check if the user is admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $employee_id = intval($_POST['employee_id']); // Use intval to sanitize input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pin_no = mysqli_real_escape_string($conn, $_POST['pin_no']);
    $department_id = intval($_POST['department']);
    $job_group_id = intval($_POST['job_group']);
    $basic_salary = floatval($_POST['basic_salary']);
    $house_allowance = floatval($_POST['house_allowance_cluster2']);
    $commuter_allowance = floatval($_POST['commuter_allowance']);
    $medical_allowance = floatval($_POST['medical_allowance']);
    $nssf = floatval($_POST['nssf']);
    $housing_levy = floatval($_POST['affordable_housing_levy']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Update employee details using a prepared statement
    $stmt = $conn->prepare("UPDATE employees SET name=?, email=?, pin_no=?, department_id=?, job_group_id=?, basic_salary=?, house_allowance_cluster2=?, commuter_allowance=?, medical_allowance=?, nssf=?, affordable_housing_levy=?, role=? WHERE id=?");
    $stmt->bind_param("sssiidddddssi", $name, $email, $pin_no, $department_id, $job_group_id, $basic_salary, $house_allowance, $commuter_allowance, $medical_allowance, $nssf, $housing_levy, $role, $employee_id);

    if ($stmt->execute()) {
        // Show a popup message using JavaScript and redirect to view_deductions.php
        echo "<script>
                alert('Employee updated successfully!');
                window.location.href='view_deductions.php';
              </script>";
        exit();
    } else {
        // Display error message
        $_SESSION['error'] = "Failed to update employee. Please try again.";
        header('Location: admin_dashboard.php');
        exit();
    }
}
?>
