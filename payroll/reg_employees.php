<?php
include('db.php'); // Include database connection

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

