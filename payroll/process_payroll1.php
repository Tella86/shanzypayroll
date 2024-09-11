<?php
session_start();
include('db.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: login.html');
    exit();
}

// Fetch employees
$employees = mysqli_query($conn, "SELECT * FROM employees");

// Initialize deductions as an associative array
$deductions = [];
$deduction_result = mysqli_query($conn, "SELECT * FROM deductions");
while ($deduction_row = mysqli_fetch_assoc($deduction_result)) {
    $deductions[$deduction_row['employee_id']] = $deduction_row;
}

if (isset($_POST['process_payroll'])) {
    $month = $_POST['month'];

    foreach ($_POST['employee_ids'] as $employee_id) {
        // Sanitize inputs and ensure they are numeric
        $basic_salary = floatval($_POST['basic_salary_' . $employee_id]);
        $house_allowance_cluster2 = floatval($_POST['house_allowance_cluster2_' . $employee_id]);
        $medical_allowance = floatval($_POST['medical_allowance_' . $employee_id]);
        $commuter_allowance = floatval($_POST['commuter_allowance_' . $employee_id]);
        $affordable_housing_levy = floatval($_POST['affordable_housing_levy_' . $employee_id]);
        $nssf = floatval($_POST['nssf_' . $employee_id]);

        // Get deductions from the database or form data
        $total_deductions = isset($deductions[$employee_id]) ? floatval($deductions[$employee_id]) : 0;

        // Calculate gross salary (Basic salary + Allowances)
        $gross_salary = $basic_salary + $house_allowance_cluster2 + $medical_allowance + $commuter_allowance + $affordable_housing_levy;

        // Calculate net salary (Gross Salary - Total Deductions)
        $net_salary = $gross_salary - $total_deductions;

        // Insert payroll record into the database
        $query = "INSERT INTO payroll (employee_id, month, basic_salary, house_allowance_cluster2, medical_allowance, commuter_allowance, affordable_housing_levy, nssf, gross_salary, total_deductions, net_salary, processed_date)
        VALUES ('$employee_id', '$month', '$basic_salary', '$house_allowance_cluster2', '$medical_allowance', '$commuter_allowance', '$affordable_housing_levy', '$nssf', '$gross_salary', '$total_deductions', '$net_salary', NOW())";

        if (!mysqli_query($conn, $query)) {
            die('Error: ' . mysqli_error($conn)); // Error handling
        }
    }

    echo "Payroll processed for the month of $month!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payroll</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
   body {
    padding-top: 20px;
}

h2 {
    margin-bottom: 20px;
}

.table td,
.table th {
    vertical-align: middle;
    font-size: 12px;
}

.form-control {
    font-size: 9px;
    padding: 3px;
    height: auto; /* Ensures the input fits well */
}

    </style>
</head>

<body>
    <div class="container">
        <h2>Process Payroll</h2>
        <form method="POST" id="payrollForm">
            <div class="form-group">
                <label for="month">Select Month</label>
                <input type="text" class="form-control" name="month" placeholder="e.g., August 2024" required>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee's Name</th>
                        <th>Basic Salary</th>
                        <th>House Allowance (Cluster 2)</th>
                        <th>Medical Allowance</th>
                        <th>Commuter Allowance</th>
                        <th>Affordable Housing Levy</th>
                        <th>N.S.S.F</th>
                        <th>Deductions</th>
                        <th>Gross Salary</th>
                        <th>Net Salary</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($employee = mysqli_fetch_assoc($employees)) { 
                        // Use the deduction data for each employee
                        $deductionData = $deductions[$employee['id']] ?? ['total_deductions' => 0];
                    ?>
                    <tr>
                        <td><?= $employee['id'] ?></td>
                        <td><?= $employee['name'] ?></td>
                        <td><input type="number" class="form-control" name="basic_salary_<?= $employee['id'] ?>" value="<?= $employee['basic_salary'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="house_allowance_cluster2_<?= $employee['id'] ?>" value="<?= $employee['house_allowance_cluster2'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="medical_allowance_<?= $employee['id'] ?>" value="<?= $employee['medical_allowance'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="commuter_allowance_<?= $employee['id'] ?>" value="<?= $employee['commuter_allowance'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="affordable_housing_levy_<?= $employee['id'] ?>" value="<?= $employee['affordable_housing_levy'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="nssf_<?= $employee['id'] ?>" value="<?= $employee['nssf'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="deductions_<?= $employee['id'] ?>"
                        value="<?= $deductionData['total_deductions'] ?>" readonly></td>
                        <td><input type="number" class="form-control gross_salary" id="gross_salary_<?= $employee['id'] ?>" value="0" readonly></td>
                        <td><input type="number" class="form-control net_salary" id="net_salary_<?= $employee['id'] ?>" value="0" readonly></td>
                        <input type="hidden" name="employee_ids[]" value="<?= $employee['id'] ?>">
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <button type="submit" name="process_payroll" class="btn btn-primary">Process Payroll</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom JS for Calculating Gross and Net Salary -->
    <script>
document.getElementById('payrollForm').addEventListener('submit', function(e) {
    let valid = true;
    document.querySelectorAll('input[type=number]').forEach(function(input) {
        if (input.value < 0) {
            alert('Values cannot be negative!');
            valid = false;
            e.preventDefault();
        }
    });
    return valid;
});

// Calculate Gross and Net Salary on form change
document.querySelectorAll('input[type=number]').forEach(function(input) {
    input.addEventListener('input', function() {
        let row = input.closest('tr');
        let basic_salary = parseFloat(row.querySelector('[name^=basic_salary]').value) || 0;
        let house_allowance = parseFloat(row.querySelector('[name^=house_allowance_cluster2]').value) || 0;
        let medical_allowance = parseFloat(row.querySelector('[name^=medical_allowance]').value) || 0;
        let commuter_allowance = parseFloat(row.querySelector('[name^=commuter_allowance]').value) || 0;
        let affordable_housing = parseFloat(row.querySelector('[name^=affordable_housing_levy]').value) || 0;
        let nssf = parseFloat(row.querySelector('[name^=nssf]').value) || 0;
        let total_deductions = parseFloat(row.querySelector('[name^=total_deductions]').value) || 0;

        // Calculate gross salary
        let gross_salary = basic_salary + house_allowance + medical_allowance + commuter_allowance + $affordable_housing_levy;

        // Calculate net salary
        let net_salary = gross_salary - (total_deductions);

        // Update the values in the respective fields
        row.querySelector('.gross_salary').value = gross_salary.toFixed(2);
        row.querySelector('.net_salary').value = net_salary.toFixed(2);
    });
});
</script>

</body>

</html>
