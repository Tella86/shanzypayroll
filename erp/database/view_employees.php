<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Employee List</h2>
        <?php
        include 'db.php'; // Database connection

        $result = $conn->query("SELECT employees.id, users.name, users.email, employees.position, employees.salary, employees.hire_date 
                                FROM employees 
                                JOIN users ON employees.user_id = users.id");

        echo '<table class="table table-bordered table-striped">';
        echo '<thead><tr>
            <th>Name</th>
            <th>Email</th>
            <th>Position</th>
            <th>Salary</th>
            <th>Hire Date</th>
            <th>Actions</th>
        </tr></thead><tbody>';

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['position']}</td>
                <td>{$row['salary']}</td>
                <td>{$row['hire_date']}</td>
                <td>
                    <a href='edit_employee.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
                    <a href='delete_employee.php?id={$row['id']}' class='btn btn-sm btn-danger'>Delete</a>
                </td>
            </tr>";
        }

        echo '</tbody></table>';
        ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>
</html>
