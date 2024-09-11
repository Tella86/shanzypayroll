<?php
session_start();
include('db.php'); // Include database connection

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}


// Fetch departments for the dropdown
$departments = mysqli_query($conn, "SELECT * FROM departments");

// Fetch job groups for the dropdown
$jobgroups = mysqli_query($conn, "SELECT * FROM jobgroup");

// Check if employee ID is passed
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
}

// Process payroll update
if (isset($_POST['update_employee'])) {
    // Retrieve form values
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pin_no = $_POST['pin_no'];
    $department_id = $_POST['department'];
    $job_group = $_POST['job_group'];
    $basic_salary = $_POST['basic_salary'];
    $house_allowance_cluster2 = $_POST['house_allowance_cluster2'];
    $medical_allowance = $_POST['medical_allowance'];
    $commuter_allowance = $_POST['commuter_allowance'];
    $nssf = $_POST['nssf'];
    $affordable_housing_levy = $_POST['affordable_housing_levy'];
    $role = $_POST['role'];

    // Validate if job group is selected
    if (empty($job_group)) {
        echo "Please select a valid job group.";
        exit();
    }

    // Update employee data in employees table
    $query = "UPDATE employees SET 
                name='$name', 
                email='$email', 
                pin_no='$pin_no', 
                department_id='$department_id', 
                job_group_id='$job_group', 
                basic_salary='$basic_salary', 
                house_allowance_cluster2='$house_allowance_cluster2', 
                medical_allowance='$medical_allowance', 
                commuter_allowance='$commuter_allowance', 
                nssf='$nssf', 
                affordable_housing_levy='$affordable_housing_levy', 
                role='$role' 
              WHERE id='$employee_id'";

    if (mysqli_query($conn, $query)) {
        echo "Employee updated successfully!";
        header('location: deductions.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>
    <div class="container">
        <h2>Edit Employee</h2>
        <form method="POST" action="">
            <!-- Existing Fields -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" value="<?= $employee['name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" value="<?= $employee['email'] ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">PIN</label>
                <input type="pin_no" class="form-control" name="pin_no" value="<?= $employee['pin_no'] ?>" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <select class="form-control" name="department" required>
                    <option value="" disabled>Select Department</option>
                    <?php while ($department = mysqli_fetch_assoc($departments)) { ?>
                        <option value="<?= $department['id'] ?>" <?= ($department['id'] == $employee['department_id']) ? 'selected' : '' ?>><?= $department['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="job_group">Job Group</label>
                <select class="form-control" name="job_group" id="job_group" required>
                    <option value="" disabled>Select Job Group</option>
                    <?php 
                    while ($jobgroup = mysqli_fetch_assoc($jobgroups)) { ?>
                        <option value="<?= $jobgroup['id'] ?>" <?= ($jobgroup['id'] == $employee['job_group_id']) ? 'selected' : '' ?>><?= $jobgroup['job_group'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Existing Fields for Salary, Role, and Password -->
            <div class="form-group">
                <label for="basic_salary">Basic Salary</label>
                <select class="form-control" name="basic_salary" id="basic_salary" required>
                    <option value="<?= $employee['basic_salary'] ?>" selected><?= $employee['basic_salary'] ?></option>
                </select>
            </div>

            <!-- Hidden fields for Allowances -->
            <input type="hidden" name="house_allowance_cluster2" id="house_allowance_cluster2" value="<?= $employee['house_allowance_cluster2'] ?>">
            <input type="hidden" name="medical_allowance" id="medical_allowance" value="<?= $employee['medical_allowance'] ?>">
            <input type="hidden" name="commuter_allowance" id="commuter_allowance" value="<?= $employee['commuter_allowance'] ?>">
            <input type="hidden" name="nssf" id="nssf" value="<?= $employee['nssf'] ?>">
            <input type="hidden" name="affordable_housing_levy" id="affordable_housing_levy" value="<?= $employee['affordable_housing_levy'] ?>">

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

    <script>
        $(document).ready(function () {
            $('#job_group').change(function () {
                var job_group_id = $(this).val();

                $.ajax({
                    url: 'salary_scale.php',
                    type: 'POST',
                    data: { job_group_id: job_group_id },
                    dataType: 'json',
                    success: function (data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            // Populate the basic_salary select with options
                            $('#basic_salary').empty(); // Clear previous options
                            var starting_salary = parseFloat(data.starting_salary);
                            var yearly_increment = parseFloat(data.yearly_increment);
                            var max_salary = parseFloat(data.max_salary);

                            for (var salary = starting_salary; salary <= max_salary; salary += yearly_increment) {
                                $('#basic_salary').append('<option value="' + salary.toFixed(2) + '">' + salary.toFixed(2) + '</option>');
                            }

                            // Set hidden values for house allowance, medical allowance, etc.
                            $('#house_allowance_cluster2').val(parseFloat(data.house_allowance_cluster2).toFixed(2));
                            $('#medical_allowance').val(parseFloat(data.medical_allowance).toFixed(2));
                            $('#commuter_allowance').val(parseFloat(data.commuter_allowance).toFixed(2));
                            $('#nssf').val(parseFloat(data.nssf).toFixed(2));

                            calculateAffordableHousingLevy();
                        }
                    }
                });
            });

            function calculateAffordableHousingLevy() {
                var salary = parseFloat($('#basic_salary').val()) || 0;
                var commuterAllowance = parseFloat($('#commuter_allowance').val()) || 0;
                var houseAllowanceCluster2 = parseFloat($('#house_allowance_cluster2').val()) || 0;
                var medicalAllowance = parseFloat($('#medical_allowance').val()) || 0;

                var levy = (salary + commuterAllowance + houseAllowanceCluster2 + medicalAllowance) * 0.015;
                var roundedLevy = Math.round(levy); // Round to nearest whole number
                $('#affordable_housing_levy').val(roundedLevy);
            }
        });
    </script>
</body>

</html>
