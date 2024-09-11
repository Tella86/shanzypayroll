<?php
include('db.php'); // Database connection

if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];
    
    // Fetch employee details
    $query = "SELECT * FROM employees WHERE id = '$employee_id'";
    $result = mysqli_query($conn, $query);

    if ($employee = mysqli_fetch_assoc($result)) {
        echo json_encode($employee);
    } else {
        echo json_encode(['error' => 'Employee not found']);
    }
}
?>
