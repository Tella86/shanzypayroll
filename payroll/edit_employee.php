<?php
session_start();
include('db.php'); // Database connection

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Check if the employee ID is passed as a query parameter
if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    // Fetch employee details from the database
    $query = "SELECT * FROM employees WHERE id = '$employee_id'";
    $result = mysqli_query($conn, $query);
    $employee = mysqli_fetch_assoc($result);

    if (!$employee) {
        echo "Employee not found.";
        exit();
    }

    // Fetch departments for the department dropdown
    $departments = mysqli_query($conn, "SELECT * FROM departments");

    // Handle form submission for updating employee details
    if (isset($_POST['update_employee'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pin_no = $_POST['pin_no'];
        $department_id = $_POST['department'];
        $job_group = $_POST['job_group'];
        $basic_salary = $_POST['basic_salary'];
        $house_allowance_cluster2 = $_POST['house_allowance_cluster2'];
        $medical_allowance = $_POST['medical_allowance'];
        $commuter_allowance = $_POST['commuter_allowance']; // Added this field
        $nssf = $_POST['nssf'];
        $affordable_housing_levy = $_POST['affordable_housing_levy'];
        $role = $_POST['role'];

        // Update employee details in the database
        $update_query = "UPDATE employees SET 
            name = '$name', 
            email = '$email', 
            pin_no = '$pin_no', 
            department_id = '$department_id', 
            basic_salary = '$basic_salary', 
            role = '$role' 
            WHERE id = '$employee_id'";

        if (mysqli_query($conn, $update_query)) {
            echo "Employee updated successfully.";
            // Redirect back to the employee list (view_employees.php)
            header('Location: admin_dashboard.php');
            exit();
        } else {
            echo "Error updating employee: " . mysqli_error($conn);
        }
    }
} else {
    echo "No employee ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Edit Employee</h2>
    <form method="POST">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" value="<?= $employee['name'] ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" value="<?= $employee['email'] ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Pin</label>
            <input type="tel" class="form-control" name="pin_no" value="<?= $employee['pin_no'] ?>" required>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <select class="form-control" name="department" required>
                <?php while ($department = mysqli_fetch_assoc($departments)) { ?>
                    <option value="<?= $department['id'] ?>" <?= ($employee['department_id'] == $department['id']) ? 'selected' : '' ?>>
                        <?= $department['name'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="basic_salary">Basic Salary</label>
            <input type="number" class="form-control" name="basic_salary" value="<?= $employee['basic_salary'] ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" name="role" required>
                <option value="staff" <?= ($employee['role'] == 'staff') ? 'selected' : '' ?>>Staff</option>
                <option value="admin" <?= ($employee['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" name="update_employee" class="btn btn-primary">Update Employee</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
