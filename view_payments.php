<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: login.html');
    exit();
}
include('db.php');

$payments = mysqli_query($conn, "
    SELECT p.*, s.name AS student_name, f.category_name 
    FROM payments p 
    JOIN students s ON p.student_id = s.id 
    JOIN fees_categories f ON p.category_id = f.id
");

if (isset($_POST['approve_payment'])) {
    $payment_id = $_POST['payment_id'];
    mysqli_query($conn, "UPDATE payments SET payment_status = 'Approved' WHERE id = '$payment_id'");
    echo "Payment approved!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>View Payments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Category</th>
                <th>Amount Paid</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($payment = mysqli_fetch_assoc($payments)) { ?>
                <tr>
                    <td><?= $payment['student_name'] ?></td>
                    <td><?= $payment['category_name'] ?></td>
                    <td><?= $payment['amount_paid'] ?></td>
                    <td><?= $payment['payment_date'] ?></td>
                    <td><?= $payment['payment_status'] ?></td>
                    <td>
                        <?php if ($payment['payment_status'] == 'Pending') { ?>
                            <form method="POST">
                                <input type="hidden" name="payment_id" value="<?= $payment['id'] ?>">
                                <button type="submit" name="approve_payment" class="btn btn-success">Approve</button>
                            </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
