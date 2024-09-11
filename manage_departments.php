<?php include('db.php'); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department_name = $_POST['department_name'];
    
    $query = "INSERT INTO departments (department_name) VALUES ('$department_name')";
    $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Departments</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="department_name">Department Name</label>
                <input type="text" name="department_name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success mt-3">Add Department</button>
        </form>

        <h3 class="mt-5">List of Departments</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM departments";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['department_id']}</td>
                            <td>{$row['department_name']}</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
