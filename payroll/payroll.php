<?php
include'db.php';
// Process payroll
if (isset($_POST['process_payroll'])) {
    $month = $_POST['month'];

    // Validate month
    if (empty($month)) {
        echo "Please select a month.";
        exit();
    }

    // Process payroll for each employee
    foreach ($_POST['employee_ids'] as $employee_id) {
        $basic_salary = $_POST['basic_salary_' . $employee_id];
        $house_allowance_cluster2 = $_POST['house_allowance_cluster2_' . $employee_id];
        $medical_allowance = $_POST['medical_allowance_' . $employee_id];
        $commuter_allowance = $_POST['commuter_allowance_' . $employee_id];
        $affordable_housing_levy = $_POST['affordable_housing_levy_' . $employee_id];
        $nssf = $_POST['nssf_' . $employee_id];
        $deductions = $_POST['deductions_' . $employee_id];

        // Validate input
        if (empty($basic_salary) || empty($house_allowance_cluster2) || empty($medical_allowance) || empty($commuter_allowance) || empty($affordable_housing_levy) || empty($nssf) || empty($deductions)) {
            echo "Please fill in all fields.";
            exit();
        }

        // Calculate gross salary
        $gross_salary = $basic_salary + $house_allowance_cluster2 + $medical_allowance + $commuter_allowance + $affordable_housing_levy + $nssf;

        // Calculate total deductions
        $total_deductions = $deductions;

        // Calculate net salary
        $net_salary = $gross_salary - $total_deductions;

        // Prepare query to insert payroll record
        $stmt = $conn->prepare("INSERT INTO payroll (employee_id, month, basic_salary, house_allowance_cluster2, medical_allowance, commuter_allowance, affordable_housing_levy, nssf, gross_salary, deductions, net_salary, processed_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        // Bind parameters
        $stmt->bind_param("isiiiiiiiii", $employee_id, $month, $basic_salary, $house_allowance_cluster2, $medical_allowance, $commuter_allowance, $affordable_housing_levy, $nssf, $gross_salary, $total_deductions, $net_salary);

        // Execute query
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
            exit();
        }
    }

    echo "Payroll processed for the month of $month!";
    header('location: admin_dashboard.php');
}

?>