<?php
session_start();
include('db.php');

// Redirect to login if not admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$employees = [];
$payroll_records = [];

// Handle month selection
$month = $_POST['month'] ?? $_GET['month'] ?? date('F Y'); // Default to current month if not set

// Prepare query to get employees
$stmt = $conn->prepare("SELECT * FROM employees");
$stmt->execute();
$result = $stmt->get_result();

// Fetch employees
while ($employee = $result->fetch_assoc()) {
    $employees[$employee['id']] = $employee;
}

// Prepare query to get payroll records
$stmt = $conn->prepare("SELECT * FROM payroll WHERE month = ?");
$stmt->bind_param('s', $month);
$stmt->execute();
$result = $stmt->get_result();

// Fetch payroll records
while ($payroll_row = $result->fetch_assoc()) {
    $payroll_records[$payroll_row['employee_id']] = $payroll_row;
}

// Function to generate months dropdown
function getMonthsDropdown($selectedMonth) {
    $months = [];
    for ($i = 0; $i < 12; $i++) {
        $months[] = date('F Y', strtotime("first day of -$i month"));
    }
    $dropdown = '<select class="form-control" name="month">';
    foreach ($months as $month) {
        $selected = ($month == $selectedMonth) ? 'selected' : '';
        $dropdown .= "<option value=\"$month\" $selected>$month</option>";
    }
    $dropdown .= '</select>';
    return $dropdown;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payroll</title>
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
        font-size: 12px;
        padding: 5px;
    }

    .form-group {
        margin-bottom: 20px;
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

        <!-- Form to select the month -->
        <form method="POST" action="view_proceed_payroll.php" target="_blank">
            <div class="form-group">
                <label for="month">Select Month to View Payroll</label>
                <?= getMonthsDropdown($month); ?>
            </div>
            <button type="submit" class="btn btn-primary" target="_blank">View Payroll</button>
        </form>

       

        
        <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-8">
                        <h3>Payroll for <?= htmlspecialchars($month) ?></h3>
                        </div>
                        <div class="col-xs-4 text-right">
                        
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
                    <th>House Allowance</th>
                    <th>Medical Allowance</th>
                    <th>Commuter Allowance</th>
                    <th>Affordable Housing</th>
                    <th>N.S.S.F</th>
                    <th>Deductions</th>
                    <th>Gross Salary</th>
                    <th>Net Salary</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Initialize total variables
                $totalDeductions = 0;
                $totalGrossSalary = 0;
                $totalNetSalary = 0;

                foreach ($employees as $employee) { 
                    $payroll = $payroll_records[$employee['id']] ?? null;
                    if ($payroll) {
                        // Accumulate totals
                        $totalDeductions += $payroll['deductions'];
                        $totalGrossSalary += $payroll['gross_salary'];
                        $totalNetSalary += $payroll['net_salary'];
                ?>
                <tr>
                    <td><?= $employee['id'] ?></td>
                    <td><?= $employee['name'] ?></td>
                    <td><?= number_format($payroll['basic_salary'], 2) ?></td>
                    <td><?= number_format($payroll['house_allowance_cluster2'], 2) ?></td>
                    <td><?= number_format($payroll['medical_allowance'], 2) ?></td>
                    <td><?= number_format($payroll['commuter_allowance'], 2) ?></td>
                    <td><?= number_format($payroll['affordable_housing_levy'], 2) ?></td>
                    <td><?= number_format($payroll['nssf'], 2) ?></td>
                    <td><?= number_format($payroll['deductions'], 2) ?></td>
                    <td><?= number_format($payroll['gross_salary'], 2) ?></td>
                    <td><?= number_format($payroll['net_salary'], 2) ?></td>
                    <td>
                        <a href="generate_salary_pdf.php?employee_id=<?= $employee['id'] ?>&month=<?= urlencode($month) ?>"
                            class="btn btn-sm btn-info" target="_blank">Generate Pay Slip</a>
                    </td>
                    
                </tr>

                <?php } } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="8">Total</th>
                    <th><?= number_format($totalDeductions, 2) ?></th>
                    <th><?= number_format($totalGrossSalary, 2) ?></th>
                    <th><?= number_format($totalNetSalary, 2) ?></th>
                </tr>
            </tfoot>
        </table>
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
</body>

</html>