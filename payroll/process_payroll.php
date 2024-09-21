<?php
session_start();
include('db.php');

// Redirect to login if not admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$employees = [];
$deductions = [];

// Prepare query to get employees
$stmt = $conn->prepare("SELECT * FROM employees");
$stmt->execute();
$result = $stmt->get_result();

// Fetch employees
while ($employee = $result->fetch_assoc()) {
    $employees[$employee['id']] = $employee;
}

// Prepare query to get deductions
$stmt = $conn->prepare("SELECT * FROM deductions");
$stmt->execute();
$result = $stmt->get_result();

// Fetch deductions
while ($deduction_row = $result->fetch_assoc()) {
    $deductions[$deduction_row['employee_id']] = $deduction_row;
}
// Process payroll
if (isset($_POST['process_payroll'])) {
    $month = $_POST['month'];

    // Validate month
    if (empty($month)) {
        echo "Please select a month.";
        exit();
    }

    // Process payroll for each employee
    foreach ($_POST['employee_ids'] as $employee_id) {
        $basic_salary = $_POST['basic_salary_' . $employee_id];
        $house_allowance_cluster2 = $_POST['house_allowance_cluster2_' . $employee_id];
        $medical_allowance = $_POST['medical_allowance_' . $employee_id];
        $commuter_allowance = $_POST['commuter_allowance_' . $employee_id];
        $affordable_housing_levy = $_POST['affordable_housing_levy_' . $employee_id];
        $nssf = $_POST['nssf_' . $employee_id];
        $deductions = $_POST['deductions_' . $employee_id];

        // Validate input
        if (empty($basic_salary) || empty($house_allowance_cluster2) || empty($medical_allowance) || empty($commuter_allowance) || empty($affordable_housing_levy) || empty($nssf) || empty($deductions)) {
            echo "Please fill in all fields.";
            exit();
        }

        // Calculate gross salary
        $gross_salary = $basic_salary + $house_allowance_cluster2 + $medical_allowance + $commuter_allowance + $affordable_housing_levy + $nssf;

        // Calculate total deductions
        $total_deductions = $deductions;

        // Calculate net salary
        $net_salary = $gross_salary - $total_deductions;

        // Prepare query to insert payroll record
        $stmt = $conn->prepare("INSERT INTO payroll (employee_id, month, basic_salary, house_allowance_cluster2, medical_allowance, commuter_allowance, affordable_housing_levy, nssf, gross_salary, deductions, net_salary, processed_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        // Bind parameters
        $stmt->bind_param("isiiiiiiiii", $employee_id, $month, $basic_salary, $house_allowance_cluster2, $medical_allowance, $commuter_allowance, $affordable_housing_levy, $nssf, $gross_salary, $total_deductions, $net_salary);

        // Execute query
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
            exit();
        }
    }

    echo "Payroll processed for the month of $month!";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payroll</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
     <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/datatables.css">
  <link rel="stylesheet" href="assets/css/datatables.min.css">
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="https://cdn.datatables.net/v/dt/dt-2.1.7/datatables.min.css" rel="stylesheet">
 
<script src="https://cdn.datatables.net/v/dt/dt-2.1.7/datatables.min.js"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.css" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <style>
        body {
            padding-top: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .table td,
        .table th {
            vertical-align: middle;
            font-size: 12px;
        }

        .form-control {
            font-size: 9px;
            padding: 3px;
            height: auto; /* Ensures the input fits well */
        }
        .demo{ font-family: 'Poppins', sans-serif; }
.panel{
    padding: 10px;
    border-radius: 0;
}
.panel .panel-heading{ padding: 20px 15px 0; }
.panel .panel-heading .title{
    color: #222;
    font-size: 20px;
    font-weight: 600;
    line-height: 30px;
    margin: 0;
}
.panel .panel-heading .btn{
    background-color: #6D7AE0;
    padding: 6px 12px;
    border-radius: 0;
    border: none;
    transition: all 0.3s ease 0s;
}
.panel .panel-heading .btn:hover{
    text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
    box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.08);
}
.panel .panel-body .table{
    margin: 0;
    border: 1px solid #e7e7e7;
}
.panel .panel-body .table tr td{ border-color: #e7e7e7; }
.panel .panel-body .table thead tr.active th{
    color: #fff;
    background-color: #6D7AE0;
    font-size: 16px;
    font-weight: 500;
    padding: 12px;
    border: 1px solid #6D7AE0;
}
.panel .panel-body .table tbody tr:hover{ background-color: rgba(109, 122, 224, 0.1); }
.panel .panel-body .table tbody tr td{
    color: #555;
    padding: 8px 12px;
    vertical-align: middle;
}
.panel .panel-body .table tbody .btn{
    border-radius: 0;
    transition: all 0.3s ease;
}
.panel .panel-body .table tbody .btn:hover{ box-shadow: 0 0 0 2px #333; }
.panel .panel-footer{
    background-color: #fff;
    padding: 0 15px 5px;
    border: none;
}
.panel .panel-footer .col{ line-height: 35px; }
.pagination{ margin: 0; }
.pagination li a{
    font-size: 15px;
    font-weight: 600;
    color: #6D7AE0;
    text-align: center;
    height: 35px;
    width: 35px;
    line-height: 33px;
    padding: 0;
    margin: 0 2px;
    display: block;
    transition: all 0.3s ease 0s;
}
.pagination li:first-child a,
.pagination li:last-child a{
    border-radius: 0;
}
.pagination li a:hover,
.pagination li a:focus,
.pagination li.active a{
    color: #fff;
    background-color: #6D7AE0;
}
/* General form styling */



/* Label styling */
.form-group label {
    display: block;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

/* Input styling */
.form-group input[type="text"] {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

/* Input focus effect */
.form-group input[type="text"]:focus {
    border-color: #007bff;
    outline: none;
}

/* Placeholder styling */
::placeholder {
    color: #888;
    font-style: italic;
}

/* Submit button styling (if needed) */
#payrollForm button[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

#payrollForm button[type="submit"]:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
      <img src="imgs/logo.jpg"  class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">Material Dashboard 2</span>
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
          <a class="nav-link text-white " href="view_employees.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">book</i>
            </div>
            <span class="nav-link-text ms-1">View Payroll</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages</h6>
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
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
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
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-material-dashboard">Online Builder</a>
            </li>
            <li class="mt-2">
              <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
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
              <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
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
                        <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
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
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <title>credit-card</title>
                          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                              <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(453.000000, 454.000000)">
                                  <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                  <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
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
        <form method="POST" id="payrollForm" action="payroll.php">
            <div class="form-group">
                <label for="month">Select Month</label>
                <input type="text" class="form-control" name="month" placeholder="e.g., August 2024" required>
            </div>

            <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-8">
                            <h4 class="title">Process Payroll <small>(All Data List)</small></h4>
                        </div>
                        <div class="col-xs-4 text-right">
                            <a href="#" class="btn btn-sm btn-primary"> Create New</a>
                        </div><br>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-hover">
        <thead>
        <tr class="active">
                        <th>ID</th>
                        <th>Employee's Name</th>
                        <th>Basic Salary</th>
                        <th>House Allo</th>
                        <th>Medical Allo</th>
                        <th>Commuter Allo</th>
                        <th>A.H.Levy</th>
                        <th>N.S.S.F</th>
                        <th>Deductions</th>
                        <th>Gross Salary</th>
                        <th>Net Salary</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($employees as $employee) { 
                        // Use the deduction data for each employee
                        $deductionData = $deductions[$employee['id']] ?? ['total_deductions' => 0];
                    ?>
                    <tr>
                        <td><?= $employee['id'] ?></td>
                        <td><?= $employee['name'] ?></td>
                        <td><input type="number" class="form-control" name="basic_salary_<?= $employee['id'] ?>" value="<?= $employee['basic_salary'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="house_allowance_cluster2_<?= $employee['id'] ?>" value="<?= $employee['house_allowance_cluster2'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="medical_allowance_<?= $employee['id'] ?>" value="<?= $employee['medical_allowance'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="commuter_allowance_<?= $employee['id'] ?>" value="<?= $employee['commuter_allowance'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="affordable_housing_levy_<?= $employee['id'] ?>" value="<?= $employee['affordable_housing_levy'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="nssf_<?= $employee['id'] ?>" value="<?= $employee['nssf'] ?>" readonly></td>
                        <td><input type="number" class="form-control" name="deductions_<?= $employee['id'] ?>" value="<?= $deductionData['total_deductions'] ?>"></td>
                        <td><input type="number" class="form-control gross_salary" id="gross_salary_<?= $employee['id'] ?>" value="0" readonly></td>
                        <td><input type="number" class="form-control net_salary" id="net_salary_<?= $employee['id'] ?>" value="0" readonly></td>
                        <input type="hidden" name="employee_ids[]" value="<?= $employee['id'] ?>">
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <button type="submit" name="process_payroll" class="btn btn-primary">Process Payroll</button>
        </form>
    </div>
    <div class="panel-footer">
                    <div class="row">
                        <div class="col col-xs-4">Page 1 of 5</div>
                        <div class="col-xs-8">
                            <ul class="pagination hidden-xs pull-right">
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                            </ul>
                            <ul class="pagination visible-xs pull-right">
                                <li><a href="#"><<</a></li>
                                <li><a href="#">>></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

       <!-- Custom JS for Calculating Gross and Net Salary -->
       <script>
        document.getElementById('payrollForm').addEventListener('submit', function(e) {
            let valid = true;
            document.querySelectorAll('input[type=number]').forEach(function(input) {
                if (input.value < 0) {
                    alert('Values cannot be negative!');
                    valid = false;
                    e.preventDefault();
                }
            });
            return valid;
        });

        // Calculate Gross and Net Salary on form change
        document.querySelectorAll('input[type=number]').forEach(function(input) {
            input.addEventListener('input', function() {
                let row = input.closest('tr');
                let basic_salary = parseFloat(row.querySelector('[name^=basic_salary]').value) || 0;
                let house_allowance = parseFloat(row.querySelector('[name^=house_allowance_cluster2]').value) || 0;
                let medical_allowance = parseFloat(row.querySelector('[name^=medical_allowance]').value) || 0;
                let commuter_allowance = parseFloat(row.querySelector('[name^=commuter_allowance]').value) || 0;
                let affordable_housing = parseFloat(row.querySelector('[name^=affordable_housing_levy]').value) || 0;
                let nssf = parseFloat(row.querySelector('[name^=nssf]').value) || 0;
                let deductions = parseFloat(row.querySelector('[name^=deductions]').value) || 0;

                // Calculate gross salary (without housing levy and NSSF)
                let gross_salary = basic_salary + house_allowance + medical_allowance + commuter_allowance;

                // Calculate net salary (Gross salary - all deductions)
                let net_salary = gross_salary - (deductions + affordable_housing + nssf);

                // Update the values in the respective fields
                row.querySelector('.gross_salary').value = gross_salary.toFixed(2);
                row.querySelector('.net_salary').value = net_salary.toFixed(2);
            });
        });
    </script>
</body>

</html>