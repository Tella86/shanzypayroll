<?php
session_start();
include('db.php'); // Include database connection

// Fetch departments for the dropdown
$departments = mysqli_query($conn, "SELECT * FROM departments");

// Fetch job groups for the dropdown
$jobgroups = mysqli_query($conn, "SELECT * FROM jobgroup");

// Process payroll
if (isset($_POST['register_employee'])) {
    // Retrieve form values
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
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Validate if job group is selected
    if (empty($job_group)) {
        echo "Please select a valid job group.";
        exit();
    }

    // Insert employee data into employees table
    $query = "INSERT INTO employees (name, email, pin_no, department_id, job_group_id, basic_salary, house_allowance_cluster2, medical_allowance, commuter_allowance, nssf, affordable_housing_levy, role) 
              VALUES ('$name', '$email', '$pin_no', '$department_id', '$job_group', '$basic_salary', '$house_allowance_cluster2', '$medical_allowance', '$commuter_allowance', '$nssf', '$affordable_housing_levy', '$role')";
    
    if (mysqli_query($conn, $query)) {
        // Get the inserted employee's ID
        $employee_id = mysqli_insert_id($conn);

        // Insert login credentials into users table
        $query = "INSERT INTO users (employee_id, email, password, role) 
                  VALUES ('$employee_id', '$email', '$password', '$role')";
        mysqli_query($conn, $query);

        echo "Employee and user account created successfully!";
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
    <title>Register Employee</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>
    <div class="container">
        <h2>Register Employee</h2>
        <form method="POST" action="reg_employees.php">
            <!-- Existing Fields -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">PIN</label>
                <input type="pin_no" class="form-control" name="pin_no" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <select class="form-control" name="department" required>
                    <option value="" disabled selected>Select Department</option>
                    <?php while ($department = mysqli_fetch_assoc($departments)) { ?>
                        <option value="<?= $department['id'] ?>"><?= $department['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="job_group">Job Group</label>
                <select class="form-control" name="job_group" id="job_group" required>
                    <option value="" disabled selected>Select Job Group</option>
                    <?php while ($jobgroup = mysqli_fetch_assoc($jobgroups)) { ?>
                        <option value="<?= $jobgroup['id'] ?>"><?= $jobgroup['job_group'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Existing Fields for Salary, Role, and Password -->
            <div class="form-group">
                <label for="basic_salary">Basic Salary</label>
                <select class="form-control" name="basic_salary" id="basic_salary" required>
                    <option value="" disabled selected>Select Salary Scale</option>
                </select>
            </div>

            <!-- Hidden fields for Allowances -->
            <input type="hidden" name="house_allowance_cluster2" id="house_allowance_cluster2">
            <input type="hidden" name="medical_allowance" id="medical_allowance">
            <input type="hidden" name="commuter_allowance" id="commuter_allowance">
            <input type="hidden" name="nssf" id="nssf">
            <input type="hidden" name="affordable_housing_levy" id="affordable_housing_levy">

            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" name="role" required>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" name="register_employee" class="btn btn-primary">Register Employee</button>
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
