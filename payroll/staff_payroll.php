<?php
session_start();
include('db.php');

if ($_SESSION['role'] != 'staff') {
    header('Location: login.html');
    exit();
}

$employee_id = $_SESSION['user_id'];
$payrolls = mysqli_query($conn, "SELECT * FROM payroll WHERE employee_id = '$employee_id'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Payroll</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>My Payroll History</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Basic Salary</th>
                    <th>Bonus</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Processed Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($payroll = mysqli_fetch_assoc($payrolls)) { ?>
                    <tr>
                        <td><?= $payroll['month'] ?></td>
                        <td><?= $payroll['basic_salary'] ?></td>
                        <td><?= $payroll['bonus'] ?></td>
                        <td><?= $payroll['deductions'] ?></td>
                        <td><?= $payroll['net_salary'] ?></td>
                        <td><?= $payroll['processed_date'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
