<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: login.html');
    exit();
}

include('db.php');

if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $amount = $_POST['amount'];
    $query = "INSERT INTO fees_categories (category_name, amount) VALUES ('$category_name', '$amount')";
    mysqli_query($conn, $query);
    echo "Fee category added successfully!";
}

$categories = mysqli_query($conn, "SELECT * FROM fees_categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fees</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Manage Fee Categories</h2>
    <form method="POST">
        <div class="form-group">
            <label for="category_name">Fee Category Name</label>
            <input type="text" class="form-control" name="category_name" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" class="form-control" name="amount" step="0.01" required>
        </div>
        <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
    </form>

    <h3>Fee Categories</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Category</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                <tr>
                    <td><?= $category['category_name'] ?></td>
                    <td><?= $category['amount'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
