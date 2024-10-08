<?php
session_start();
include('db.php'); // Database connection

// Check if the user is admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch all employees from the database with department and job group information
$query = "SELECT e.*, d.designation_name AS designation_name, j.job_group AS job_group_name 
          FROM employees e 
          JOIN designation d ON e.designation_id = d.id 
          JOIN jobgroup j ON e.job_group_id = j.id 
          ORDER BY e.id ASC";
$employees = mysqli_query($conn, $query);

// Fetch departments, designations, and job groups for the dropdowns
$departments = mysqli_query($conn, "SELECT * FROM departments");
$designations = mysqli_query($conn, "SELECT * FROM designation");
$jobgroups = mysqli_query($conn, "SELECT * FROM jobgroup");

// Check if the employee ID is passed as a query parameter
if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']); // Use intval to sanitize input

    // Fetch employee details from the database using a prepared statement
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    if (!$employee) {
        echo "Employee not found.";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Employee Dashboard</title>
    <style>
        body {
            padding-top: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .table td, .table th {
            vertical-align: middle;
            font-size: 12px;
        }
        .form-control {
            font-size: 9px;
            padding: 3px;
            height: auto;
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
    </style>
<!--     Fonts and icons     -->
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <link href="https://cdn.datatables.net/v/dt/dt-2.1.7/datatables.min.css" rel="stylesheet">
 
<script src="https://cdn.datatables.net/v/dt/dt-2.1.7/datatables.min.js"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.css" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="dashboard.php" target="_blank">
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
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank" href="https://ezems.co.ke">Developer</a>
            </li>
            <li class="mt-2">
              <a class="github-button" href="https://github.com/Tella86/shanzypayroll" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
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
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-8">
                            <h4 class="title">Employees <small>(All Data List)</small></h4>
                        </div>
                        <div class="col-xs-4 text-right">
                            <a href="manage_employee.php" class="btn btn-sm btn-primary"> Add New Employee</a>
                        </div><div></div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-hover">
        <thead>
        <tr class="active">
                <th>ID</th>
                <th>EMPLOYEE'S NAME</th>
                <th>DESIGNATION</th>
                <th>Job Group</th>
                <th>Basic Salary</th>
                <th>Medical Allowance</th>
                <th>Commuter Allowance</th>
                <th>House Allowance</th>
                <th>NSSF</th>
                <th>Housing Levy</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($employee = mysqli_fetch_assoc($employees)) { ?>
                <tr>
                    <td><?= $employee['id'] ?></td>
                    <td><?= $employee['name'] ?></td>
                    <td><?= $employee['designation_name'] ?></td>
                    <td><?= $employee['job_group_name'] ?></td>
                    <td><?= number_format($employee['basic_salary'], 2) ?></td>
                    <td><?= number_format($employee['medical_allowance'], 2) ?></td>
                    <td><?= number_format($employee['commuter_allowance'], 2) ?></td>
                    <td><?= number_format($employee['house_allowance_cluster2'], 2) ?></td>
                    <td><?= number_format($employee['nssf'], 2) ?></td>
                    <td><?= number_format($employee['affordable_housing_levy'], 2) ?></td>
                    <td>
                        <button class="btn btn-info btn-sm editBtn" data-id="<?= $employee['id'] ?>" data-toggle="modal" data-target="#editModal"><em class="fa fa-edit"></em></button>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="<?= $employee['id'] ?>"><em class="fa fa-trash"></em></button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
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
<!-- Edit Employee Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm" method="POST" action="update_employee.php">
            <input type="hidden" name="employee_id" id="edit_employee_id">
            <div class="form-group">
                <label for="edit_name">Name</label>
                <input type="text" class="form-control" id="edit_name" name="name" required>
            </div>
            <div class="form-group">
                <label for="edit_email">Email</label>
                <input type="email" class="form-control" id="edit_email" name="email" required>
            </div>
            <div class="form-group">
                <label for="edit_pin">Pin</label>
                <input type="tel" class="form-control" id="edit_pin" name="pin_no" required>
            </div>
            <div class="form-group">
                <label for="edit_designation">Designation</label>
                <select class="form-control" id="edit_designation" name="designation_id" required>
                    <option value="" disabled selected>Select designation</option>
                    <?php while ($designation = mysqli_fetch_assoc($designations)) { ?>
                        <option value="<?= htmlspecialchars($designation['id']) ?>"><?= htmlspecialchars($designation['designation_name']) ?></option>
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

            <div class="form-group">
                <label for="edit_salary">Basic Salary</label>
                <input type="number" class="form-control" id="edit_salary" name="basic_salary" required>
            </div>
            <div class="form-group">
                <label for="edit_house_allowance_cluster2">House Allowance</label>
                <input type="number" class="form-control" id="edit_house_allowance_cluster2" name="house_allowance_cluster2" required>
            </div>
            <div class="form-group">
                <label for="edit_commuter_allowance">Commuter Allowance</label>
                <input type="number" class="form-control" id="edit_commuter_allowance" name="commuter_allowance" required>
            </div>
            <div class="form-group">
                <label for="edit_medical_allowance">Medical Allowance</label>
                <input type="number" class="form-control" id="edit_medical_allowance" name="medical_allowance" required>
            </div>
            <div class="form-group">
                <label for="edit_nssf">NSSF</label>
                <input type="number" class="form-control" id="edit_nssf" name="nssf" required>
            </div>
            <div class="form-group">
                <label for="edit_affordable_housing_levy">Affordable Housing Levy</label>
                <input type="number" class="form-control" id="edit_affordable_housing_levy" name="affordable_housing_levy" required>
            </div>
            <div class="form-group">
                <label for="edit_role">Role</label>
                <select class="form-control" id="edit_role" name="role" required>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Employee</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script to handle Edit and Delete functionality -->
<script>
    $('#manageEmployeesLink').on('click', function () {
                $('#content-area').load('manage_employees.php');
            });
    $(document).ready(function() {
        // Handle Edit Button Click
        $('.editBtn').on('click', function() {
            var employeeId = $(this).data('id');

            // Fetch employee details using AJAX
            $.ajax({
                url: 'get_employee.php',
                type: 'GET',
                data: { id: employeeId },
                success: function(data) {
                    var employee = JSON.parse(data);

                    // Populate the form fields in the modal with the employee data
                    $('#edit_employee_id').val(employee.id);
                    $('#edit_name').val(employee.name);
                    $('#edit_email').val(employee.email);
                    $('#edit_pin').val(employee.pin_no);
                    $('#edit_designation').val(employee.designation_name);
                    $('#job_group').val(employee.job_group_id);
                    $('#edit_salary').val(employee.basic_salary);
                    $('#edit_house_allowance_cluster2').val(employee.house_allowance_cluster2);
                    $('#edit_commuter_allowance').val(employee.commuter_allowance);
                    $('#edit_medical_allowance').val(employee.medical_allowance);
                    $('#edit_nssf').val(employee.nssf);
                    $('#edit_affordable_housing_levy').val(employee.affordable_housing_levy);
                    $('#edit_role').val(employee.role);
                }
            });
        });

        // Open delete confirmation modal and set employee ID
        $('.deleteBtn').on('click', function() {
            var employeeId = $(this).data('id');
            $('#employee_id').val(employeeId);
            $('#deleteModal').modal('show');
        });
    });
</script>

</body>
</html>
