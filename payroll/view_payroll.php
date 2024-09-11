<?php
session_start();
include('db.php'); // Include your database connection

// Ensure the user is logged in and is a staff member
if ($_SESSION['role'] != 'staff') {
    header('Location: login.php');
    exit();
}

// Get the logged-in staff's employee ID from the session
$employee_id = $_SESSION['user_id']; // Assuming employee ID is stored in the session

// Fetch payroll details for the logged-in employee
$query = "SELECT * FROM payroll WHERE employee_id = '$employee_id' ORDER BY processed_date DESC";
$payrolls = mysqli_query($conn, $query);
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
        table {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Payroll History</h2>
    <p>Below is the payroll history for your account.</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Month</th>
                <th>Basic Salary</th>
                <th>Bonus</th>
                <th>Deductions</th>
                <th>Net Salary</th>
                <th>Processed Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($payroll = mysqli_fetch_assoc($payrolls)) { ?>
                <tr>
                    <td><?= htmlspecialchars($payroll['month']) ?></td>
                    <td><?= number_format($payroll['basic_salary'], 2) ?></td>
                    <td><?= number_format($payroll['bonus'], 2) ?></td>
                    <td><?= number_format($payroll['deductions'], 2) ?></td>
                    <td><?= number_format($payroll['net_salary'], 2) ?></td>
                    <td><?= date('d-M-Y', strtotime($payroll['processed_date'])) ?></td>
                    <td>
                        <a href="generate_salary_pdf.php?employee_id=<?= urlencode($payroll['employee_id']) ?>&month=<?= urlencode($payroll['month']) ?>"
                            class="btn btn-sm btn-info" target="_blank">Generate Pay Slip</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
