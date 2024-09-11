<?php
session_start();
include('db.php');

// Redirect to login if not admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$employees = [];
$deductions = [];

// Prepare query to get employees
$stmt = $conn->prepare("SELECT * FROM employees");
$stmt->execute();
$result = $stmt->get_result();

// Fetch employees
while ($employee = $result->fetch_assoc()) {
    $employees[$employee['id']] = $employee;
}

// Prepare query to get deductions
$stmt = $conn->prepare("SELECT * FROM deductions");
$stmt->execute();
$result = $stmt->get_result();

// Fetch deductions
while ($deduction_row = $result->fetch_assoc()) {
    $deductions[$deduction_row['employee_id']] = $deduction_row;
}
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
        .demo{ font-family: 'Poppins', sans-serif; }
.panel{
    padding: 10px;
    border-radius: 0;
}
.panel .panel-heading{ padding: 20px 15px 0; }
.panel .panel-heading .title{
    color: #222;
    font-size: 20px;
    font-weight: 600;
    line-height: 30px;
    margin: 0;
}
.panel .panel-heading .btn{
    background-color: #6D7AE0;
    padding: 6px 12px;
    border-radius: 0;
    border: none;
    transition: all 0.3s ease 0s;
}
.panel .panel-heading .btn:hover{
    text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
    box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.08);
}
.panel .panel-body .table{
    margin: 0;
    border: 1px solid #e7e7e7;
}
.panel .panel-body .table tr td{ border-color: #e7e7e7; }
.panel .panel-body .table thead tr.active th{
    color: #fff;
    background-color: #6D7AE0;
    font-size: 16px;
    font-weight: 500;
    padding: 12px;
    border: 1px solid #6D7AE0;
}
.panel .panel-body .table tbody tr:hover{ background-color: rgba(109, 122, 224, 0.1); }
.panel .panel-body .table tbody tr td{
    color: #555;
    padding: 8px 12px;
    vertical-align: middle;
}
.panel .panel-body .table tbody .btn{
    border-radius: 0;
    transition: all 0.3s ease;
}
.panel .panel-body .table tbody .btn:hover{ box-shadow: 0 0 0 2px #333; }
.panel .panel-footer{
    background-color: #fff;
    padding: 0 15px 5px;
    border: none;
}
.panel .panel-footer .col{ line-height: 35px; }
.pagination{ margin: 0; }
.pagination li a{
    font-size: 15px;
    font-weight: 600;
    color: #6D7AE0;
    text-align: center;
    height: 35px;
    width: 35px;
    line-height: 33px;
    padding: 0;
    margin: 0 2px;
    display: block;
    transition: all 0.3s ease 0s;
}
.pagination li:first-child a,
.pagination li:last-child a{
    border-radius: 0;
}
.pagination li a:hover,
.pagination li a:focus,
.pagination li.active a{
    color: #fff;
    background-color: #6D7AE0;
}
    </style>
</head>

<body>
    
    <div class="container">
        <h2></h2>
        <form method="POST" id="payrollForm" action="payroll.php">
            <div class="form-group">
                <label for="month">Select Month</label>
                <input type="text" class="form-control" name="month" placeholder="e.g., August 2024" required>
            </div>

            <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-8">
                            <h4 class="title">Process Payroll <small>(All Data List)</small></h4>
                        </div>
                        <div class="col-xs-4 text-right">
                            <a href="#" class="btn btn-sm btn-primary"> Create New</a>
                        </div><br>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-hover">
        <thead>
        <tr class="active">
                        <th>ID</th>
                        <th>Employee's Name</th>
                        <th>Basic Salary</th>
                        <th>House Allo</th>
                        <th>Medical Allo</th>
                        <th>Commuter Allo</th>
                        <th>A.H.Levy</th>
                        <th>N.S.S.F</th>
                        <th>Deductions</th>
                        <th>Gross Salary</th>
                        <th>Net Salary</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($employees as $employee) { 
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
                        <td><input type="number" class="form-control" name="deductions_<?= $employee['id'] ?>" value="<?= $deductionData['total_deductions'] ?>"></td>
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
    <div class="panel-footer">
                    <div class="row">
                        <div class="col col-xs-4">Page 1 of 5</div>
                        <div class="col-xs-8">
                            <ul class="pagination hidden-xs pull-right">
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                            </ul>
                            <ul class="pagination visible-xs pull-right">
                                <li><a href="#"><<</a></li>
                                <li><a href="#">>></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                let deductions = parseFloat(row.querySelector('[name^=deductions]').value) || 0;

                // Calculate gross salary (without housing levy and NSSF)
                let gross_salary = basic_salary + house_allowance + medical_allowance + commuter_allowance;

                // Calculate net salary (Gross salary - all deductions)
                let net_salary = gross_salary - (deductions + affordable_housing + nssf);

                // Update the values in the respective fields
                row.querySelector('.gross_salary').value = gross_salary.toFixed(2);
                row.querySelector('.net_salary').value = net_salary.toFixed(2);
            });
        });
    </script>
</body>

</html>