<?php include('db.php'); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $query = "INSERT INTO fees (student_id, amount, due_date, status)
              VALUES ('$student_id', '$amount', '$due_date', '$status')";
    $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fees</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Fees</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" name="student_id" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="amount">Amount</label>
                <input type="number" name="amount" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="status">Payment Status</label>
                <select name="status" class="form-control">
                    <option value="Paid">Paid</option>
                    <option value="Unpaid">Unpaid</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success mt-3">Submit Fees</button>
        </form>

        <h3 class="mt-5">Fees Records</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fee ID</th>
                    <th>Student ID</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM fees";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['fee_id']}</td>
                            <td>{$row['student_id']}</td>
                            <td>{$row['amount']}</td>
                            <td>{$row['due_date']}</td>
                            <td>{$row['status']}</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
