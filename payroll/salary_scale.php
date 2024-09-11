<?php
include('db.php'); // Include database connection

if (isset($_POST['job_group_id'])) {
    $job_group_id = $_POST['job_group_id'];

    // Fetch the salary scale and house allowance for the selected job group
    $query = "SELECT starting_salary, yearly_increment, max_salary, house_allowance_cluster2, nssf, commuter_allowance, medical_allowance FROM jobgroup WHERE id = '$job_group_id'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // Return salary and house allowance data in JSON format
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No data found']);
    }
}
?>
