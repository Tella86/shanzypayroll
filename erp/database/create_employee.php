<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Employee</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Add New Employee</h2>
        <?php
        include 'db.php'; // Database connection

        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $position = $_POST['position'];
            $salary = $_POST['salary'];
            $hire_date = $_POST['hire_date'];

            $query = "INSERT INTO employees (user_id, position, salary, hire_date) 
                      VALUES ((SELECT id FROM users WHERE email = ?), ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssds", $email, $position, $salary, $hire_date);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Employee added successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
            }
        }
        ?>

        <form method="post" action="create_employee.php">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Position</label>
                <input type="text" class="form-control" id="position" name="position" required>
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" class="form-control" id="salary" name="salary" required>
            </div>
            <div class="mb-3">
                <label for="hire_date" class="form-label">Hire Date</label>
                <input type="date" class="form-control" id="hire_date" name="hire_date" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Add Employee</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>
</html>
