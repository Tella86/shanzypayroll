<?php
session_start();
if ($_SESSION['role'] != 'student' && $_SESSION['role'] != 'parent') {
    header('Location: login.html');
    exit();
}

include('db.php');

$student_id = $_SESSION['user_id'];
$categories = mysqli_query($conn, "SELECT * FROM fees_categories");
$payments = mysqli_query($conn, "
    SELECT p.*, f.category_name 
    FROM payments p 
    JOIN fees_categories f ON p.category_id = f.id 
    WHERE p.student_id = '$student_id'
");

if (isset($_POST['pay_fees'])) {
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    mysqli_query($conn, "
        INSERT INTO payments (student_id, category_id, amount_paid, payment_date, payment_status) 
        VALUES ('$student_id', '$category_id', '$amount', NOW(), 'Pending')
    ");
    echo "Payment initiated successfully! Awaiting approval.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Fees</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Pay Your Fees</h2>
    <form method="POST">
        <div class="form-group">
            <label for="category">Select Fee Category</label>
            <select class="form-control" name="category_id" required>
                <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                    <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?> (<?= $category['amount'] ?>)</option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" class="form-control" name="amount" step="0.01" required>
        </div>
        <button type="submit" name="pay_fees" class="btn btn-primary">Pay Now</button>
    </form>

    <h3>Payment History</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Category</th>
                <th>Amount Paid</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($payment = mysqli_fetch_assoc($payments)) { ?>
                <tr>
                    <td><?= $payment['category_name'] ?></td>
                    <td><?= $payment['amount_paid'] ?></td>
                    <td><?= $payment['payment_date'] ?></td>
                    <td><?= $payment['payment_status'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
include 'mpesa_functions.php';

if (isset($_POST['pay_fees'])) {
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $phone = $_POST['phone'];  // Phone number from the user
    
    // Call the lipaNaMpesaOnline function to initiate STK Push
    $response = lipaNaMpesaOnline($phone, $amount);
    
    if ($response->ResponseCode == "0") {
        echo "STK Push sent successfully. Check your phone to complete the payment.";
    } else {
        echo "Failed to initiate STK Push. Try again.";
    }
}
?>

<form method="POST">
    <div class="form-group">
        <label for="phone">M-Pesa Phone Number</label>
        <input type="tel" class="form-control" name="phone" placeholder="07XXXXXXXX" required>
    </div>
    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="number" class="form-control" name="amount" step="0.01" required>
    </div>
    <button type="submit" name="pay_fees" class="btn btn-primary">Pay with M-Pesa</button>
</form>

</body>
</html>
