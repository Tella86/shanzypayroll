<?php
include 'db.php';

if (isset($_POST['submit'])) {
    $employee_id = $_POST['employee_id'];
    $salary = $_POST['salary'];
    $bonuses = $_POST['bonuses'];
    $deductions = $_POST['deductions'];
    $payment_date = $_POST['payment_date'];

    $query = "INSERT INTO payroll (employee_id, salary, bonuses, deductions, payment_date) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iddds", $employee_id, $salary, $bonuses, $deductions, $payment_date);
    
    if ($stmt->execute()) {
        echo "Payroll generated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<form method="post" action="generate_payroll.php">
    <label>Employee ID: </label>
    <input type="number" name="employee_id" required><br>
    <label>Salary: </label>
    <input type="number" name="salary" required><br>
    <label>Bonuses: </label>
    <input type="number" name="bonuses"><br>
    <label>Deductions: </label>
    <input type="number" name="deductions"><br>
    <label>Payment Date: </label>
    <input type="date" name="payment_date" required><br>
    <input type="submit" name="submit" value="Generate Payroll">
</form>
