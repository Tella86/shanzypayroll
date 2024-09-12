<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is logged in and has either the admin or staff role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || 
    ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) {
    header('Location: login.php');
    exit();
}

// Proceed with the rest of the page if logged in as admin or staff
include 'db.php';

// Fetch the logged-in user's details
$user_id = $_SESSION['user_id'];
$query = "SELECT e.name, d.name AS department_name, j.job_group AS job_group_name 
          FROM employees e 
          JOIN departments d ON e.department_id = d.id 
          JOIN jobgroup j ON e.job_group_id = j.id 
          WHERE e.id = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $employee = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching user details.";
}

// The rest of your page content here...

?>
