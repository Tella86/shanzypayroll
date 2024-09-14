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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Material Dashboard 2 by Creative Tim
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="assets/css/material-dashboard.css" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-200">
    <aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard "
                target="_blank">
                <img src="imgs/logo.jpg"  class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold text-white">Admin Dashboard</span>
            </a>
        </div>
        <hr class="horizontal light mt-0 mb-2">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white active bg-gradient-primary" href="dashboard.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="manage_employee.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-link-text ms-1">manage_employees</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="process_payroll.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">receipt_long</i>
                        </div>
                        <span class="nav-link-text ms-1">Process Payroll</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="view_deductions.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">view_in_ar</i>
                        </div>
                        <span class="nav-link-text ms-1">View Deductions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="summary_deductions.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">format_textdirection_r_to_l</i>
                        </div>
                        <span class="nav-link-text ms-1">Summary Deductions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="view_employees.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <span class="nav-link-text ms-1">View Employees</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="view_proceed_payroll.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">book</i>
                        </div>
                        <span class="nav-link-text ms-1">View Payroll</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages
                    </h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="../pages/profile.html">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <span class="nav-link-text ms-1">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="../pages/sign-in.html">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">login</i>
                        </div>
                        <span class="nav-link-text ms-1">Sign In</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="../pages/sign-up.html">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">assignment</i>
                        </div>
                        <span class="nav-link-text ms-1">Sign Up</span>
                    </a>
                </li>
            </ul>
        </div>

    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
            data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Dashboard</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group input-group-outline">
                            <label class="form-label">Type here...</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank"
                                href="https://www.creative-tim.com/builder?ref=navbar-material-dashboard">Online
                                Builder</a>
                        </li>
                        <li class="mt-2">
                            <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard"
                                data-icon="octicon-star" data-size="large" data-show-count="true"
                                aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0">
                                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown pe-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell cursor-pointer"></i>
                            </a>
                            <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                                aria-labelledby="dropdownMenuButton">
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">New message</span> from Laur
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    13 minutes ago
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="../assets/img/small-logos/logo-spotify.svg"
                                                    class="avatar avatar-sm bg-gradient-dark  me-3 ">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">New album</span> by Travis Scott
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    1 day
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                                <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <title>credit-card</title>
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-2169.000000, -745.000000)"
                                                            fill="#FFFFFF" fill-rule="nonzero">
                                                            <g transform="translate(1716.000000, 291.000000)">
                                                                <g transform="translate(453.000000, 454.000000)">
                                                                    <path class="color-background"
                                                                        d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                                                        opacity="0.593633743"></path>
                                                                    <path class="color-background"
                                                                        d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                                    </path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    Payment successfully completed
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    2 days
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a href="../pages/sign-in.html" class="nav-link text-body font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">Sign In</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
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
        $(document).ready(function() {
            $('#job_group').change(function() {
                var job_group_id = $(this).val();

                $.ajax({
                    url: 'salary_scale.php',
                    type: 'POST',
                    data: {
                        job_group_id: job_group_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            // Populate the basic_salary select with options
                            $('#basic_salary').empty(); // Clear previous options
                            var starting_salary = parseFloat(data.starting_salary);
                            var yearly_increment = parseFloat(data.yearly_increment);
                            var max_salary = parseFloat(data.max_salary);

                            for (var salary = starting_salary; salary <=
                                max_salary; salary += yearly_increment) {
                                $('#basic_salary').append('<option value="' + salary
                                    .toFixed(2) + '">' + salary.toFixed(2) + '</option>'
                                );
                            }

                            // Set hidden values for house allowance, medical allowance, etc.
                            $('#house_allowance_cluster2').val(parseFloat(data
                                .house_allowance_cluster2).toFixed(2));
                            $('#medical_allowance').val(parseFloat(data.medical_allowance)
                                .toFixed(2));
                            $('#commuter_allowance').val(parseFloat(data.commuter_allowance)
                                .toFixed(2));
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