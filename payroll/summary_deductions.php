<?php  
session_start();
include('db.php');

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch all deductions from the database
$query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
          FROM deductions d
          JOIN employees e ON d.employee_id = e.id
          JOIN designation des ON e.designation_id = des.id";
$deductions = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Deductions Summary</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/Summary.css">
   <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
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
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
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
    <div class="container mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col col-xs-8">
                                    <h4 class="title">Employee Deductions <small>(Summary)</small></h4>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="active">
                                        <th>#</th>
                                        <th>Employee Name</th>
                                        <th style="color: white; padding: 12px;">Designation</th>
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#nhifModal"
                                                style="color: white; padding: 12px;">NHIF</button></th>
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#nssfModal"
                                                style="color: white; padding: 12px;">NSSF</button></th>
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#payeModal"
                                                style="color: white; padding: 12px;">PAYE</button></th>
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#affordableHousingModal"
                                                style="color: white; padding: 0px;">Affordable Housing</button></th>
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#payeModal2"
                                                style="color: white; padding: 0px;">FamilyBank Loan</button></th>
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#payeModal3"
                                                style="color: white; padding: 12px;">Welfare</button></th>
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#payeModal4"
                                                style="color: white; padding: 12px;">Kudheiha</button></th>
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#payeModal5"
                                                style="color: white; padding: 12px;">Advance</button></th>
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#payeModal6"
                                                style="color: white; padding: 12px;">Rent</button></th>
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#payeModal7"
                                                style="color: white; padding: 0px;">Imarika Sacco</button></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    while ($row = mysqli_fetch_assoc($deductions)) { ?>
                                    <tr>
                                        <td><?= $counter++; ?></td>
                                        <td><?= htmlspecialchars($row['employee_name']); ?></td>
                                        <td><?= htmlspecialchars($row['designation_name']); ?></td>
                                        <td><?= number_format($row['NHIF'], 2); ?></td>
                                        <td><?= number_format($row['NSSF'], 2); ?></td>
                                        <td><?= number_format($row['paye'], 2); ?></td>
                                        <td><?= number_format($row['affordable_housing_levy'], 2); ?></td>
                                        <td><?= number_format($row['family_bank_loan'], 2); ?></td>
                                        <td><?= number_format($row['welfare'], 2); ?></td>
                                        <td><?= number_format($row['kudheiha'], 2); ?></td>
                                        <td><?= number_format($row['advance'], 2); ?></td>
                                        <td><?= number_format($row['rent'], 2); ?></td>
                                        <td><?= number_format($row['imarika_sacco'], 2); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NHIF Modal -->
    <div class="modal fade" id="nhifModal" tabindex="-1" role="dialog" aria-labelledby="nhifModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="nhifContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="nhifModalLabel">NHIF Deductions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>NHIF Deduction</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
               
               // Fetch NHIF deductions only
               $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                         FROM deductions d
                         JOIN employees e ON d.employee_id = e.id
                         JOIN designation des ON e.designation_id = des.id
                         WHERE d.NHIF IS NOT NULL"; // Fetch only rows with NHIF deductions
               $deductions = mysqli_query($conn, $query);
               
               $nhifTotal = 0;
               while ($nhifRow = mysqli_fetch_assoc($deductions)) {
                   $nhifTotal += $nhifRow['NHIF'];
                   ?>
                            <tr>
                                <td><?= htmlspecialchars($nhifRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($nhifRow['designation_name']); ?></td>
                                <td><?= number_format($nhifRow['NHIF'], 2); ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($nhifTotal, 2); ?></td>
                                <!-- Total in the same column as NHIF figures -->
                            </tr>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary" onclick="printContent('nhifContent')">Print</button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF('NHIF')">Download as PDF</button>
                </div>
            </div>
        </div>
    </div>

    <!-- NSSF Modal -->
<div class="modal fade" id="nssfModal" tabindex="-1" role="dialog" aria-labelledby="nssfModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="nssfContent">
            <div class="modal-header">
                <h5 class="modal-title" id="nssfModalLabel">NSSF Deductions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>NSSF Deduction</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch NSSF deductions only
                        $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                                  FROM deductions d
                                  JOIN employees e ON d.employee_id = e.id
                                  JOIN designation des ON e.designation_id = des.id
                                  WHERE d.NSSF IS NOT NULL";
                        $deductions = mysqli_query($conn, $query);

                        $nssfTotal = 0;
                        while ($nssfRow = mysqli_fetch_assoc($deductions)) {
                            $nssfTotal += $nssfRow['NSSF'];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($nssfRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($nssfRow['designation_name']); ?></td>
                                <td><?= number_format($nssfRow['NSSF'], 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <td></td> <!-- Empty cell to maintain column structure -->
                            <td><strong><?= number_format($nssfTotal, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary" onclick="printContent('nhifContent')">Print</button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF('NHIF')">Download as PDF</button>
                </div>
            </div>
        </div>
    </div>
    <!-- PAYE Modal -->
<div class="modal fade" id="payeModal" tabindex="-1" role="dialog" aria-labelledby="payeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="payeContent">
            <div class="modal-header">
                <h5 class="modal-title" id="payeModalLabel">PAYE Deductions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>PAYE Deduction</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch PAYE deductions only
                        $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                                  FROM deductions d
                                  JOIN employees e ON d.employee_id = e.id
                                  JOIN designation des ON e.designation_id = des.id
                                  WHERE d.paye IS NOT NULL";
                        $deductions = mysqli_query($conn, $query);

                        $payeTotal = 0;
                        while ($payeRow = mysqli_fetch_assoc($deductions)) {
                            $payeTotal += $payeRow['paye'];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($payeRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($payeRow['designation_name']); ?></td>
                                <td><?= number_format($payeRow['paye'], 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <td></td> <!-- Empty cell to maintain column structure -->
                            <td><strong><?= number_format($payeTotal, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary" onclick="printContent('payeContent')">Print</button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF('paye')">Download as PDF</button>
                </div>
            </div>
        </div>
    </div>
<!-- Affordable Housing Levy Modal -->
<div class="modal fade" id="affordableHousingModal" tabindex="-1" role="dialog" aria-labelledby="affordableHousingModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="affordableHousingContent">
            <div class="modal-header">
                <h5 class="modal-title" id="affordableHousingModalLabel">Affordable Housing Deductions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Affordable Housing</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch Affordable Housing Levy deductions only
                        $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                                  FROM deductions d
                                  JOIN employees e ON d.employee_id = e.id
                                  JOIN designation des ON e.designation_id = des.id
                                  WHERE d.affordable_housing_levy IS NOT NULL";
                        $deductions = mysqli_query($conn, $query);

                        $affordableHousingTotal = 0;
                        while ($levyRow = mysqli_fetch_assoc($deductions)) {
                            $affordableHousingTotal += $levyRow['affordable_housing_levy'];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($levyRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($levyRow['designation_name']); ?></td>
                                <td><?= number_format($levyRow['affordable_housing_levy'], 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <td></td> <!-- Empty cell to maintain column structure -->
                            <td><strong><?= number_format($affordableHousingTotal, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
 
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary"
                        onclick="printContent('affordableHousingContent')">Print</button>
                    <button type="button" class="btn btn-success"
                        onclick="downloadPDF('affordablehousinglevy')">Download as PDF</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PAYE Modal (Similar to Family Bank Loan Modal) -->
    <div class="modal fade" id="payeModal2" tabindex="-1" role="dialog" aria-labelledby="payeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="familybankloanContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="payeModalLabel">Family Bank Loan Deductions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Family Bank Loan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             // Fetch NSSF deductions only
                             $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                             FROM deductions d
                             JOIN employees e ON d.employee_id = e.id
                             JOIN designation des ON e.designation_id = des.id
                             WHERE d.NSSF IS NOT NULL"; // Fetch only rows with NHIF deductions
                             $deductions = mysqli_query($conn, $query);
                            $familyBanklTotal = 0;
                            while ($loanRow = mysqli_fetch_assoc($deductions)) {
                                $familyBanklTotal += $loanRow['family_bank_loan'];
                                ?>
                            <tr>
                                <td><?= htmlspecialchars($loanRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($loanRow['designation_name']); ?></td>
                                <td><?= number_format($loanRow['family_bank_loan'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($familyBanklTotal, 2); ?></td>
                                <!-- Total in the same column as NHIF figures -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary"
                        onclick="printContent('familybankloanContent')">Print</button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF('familybankloan')">Download as
                        PDF</button>
                </div>
            </div>
        </div>
    </div>
    <!-- PAYE Modal (Similar to Welfare Modal) -->
    <div class="modal fade" id="payeModal3" tabindex="-1" role="dialog" aria-labelledby="payeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="welfareContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="payeModalLabel">Welfare Deductions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Welfare</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             // Fetch NSSF deductions only
                             $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                             FROM deductions d
                             JOIN employees e ON d.employee_id = e.id
                             JOIN designation des ON e.designation_id = des.id
                             WHERE d.NSSF IS NOT NULL"; // Fetch only rows with NHIF deductions
                             $deductions = mysqli_query($conn, $query);
                            $welfareTotal = 0;
                            while ($welfareRow = mysqli_fetch_assoc($deductions)) {
                                $welfareTotal += $welfareRow['welfare'];
                                ?>
                            <tr>
                                <td><?= htmlspecialchars($welfareRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($welfareRow['designation_name']); ?></td>
                                <td><?= number_format($welfareRow['welfare'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($welfareTotal, 2); ?></td>
                                <!-- Total in the same column as NHIF figures -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary"
                        onclick="printContent('welfareContent')">Print</button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF('welfare')">Download as
                        PDF</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PAYE Modal (Similar to Kudheiha Modal) -->
    <div class="modal fade" id="payeModal4" tabindex="-1" role="dialog" aria-labelledby="payeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="kudheihaContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="payeModalLabel">Kudheiha Deductions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Kudheiha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch NSSF deductions only
                            $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                            FROM deductions d
                            JOIN employees e ON d.employee_id = e.id
                            JOIN designation des ON e.designation_id = des.id
                            WHERE d.NSSF IS NOT NULL"; // Fetch only rows with NHIF deductions
                            $deductions = mysqli_query($conn, $query);
                            $kudheihaTotal = 0;
                            while ($kudheihaRow = mysqli_fetch_assoc($deductions)) {
                                $kudheihaTotal += $kudheihaRow['kudheiha'];
                                ?>
                            <tr>
                                <td><?= htmlspecialchars($kudheihaRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($kudheihaRow['designation_name']); ?></td>
                                <td><?= number_format($kudheihaRow['kudheiha'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($kudheihaTotal, 2); ?></td>
                                <!-- Total in the same column as NHIF figures -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary"
                        onclick="printContent('kudheihaContent')">Print</button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF('kudheiha')">Download as
                        PDF</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PAYE Modal (Similar to Advance Modal) -->
    <div class="modal fade" id="payeModal5" tabindex="-1" role="dialog" aria-labelledby="payeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="advanceContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="payeModalLabel">Advance Deductions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Advance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        // Fetch NSSF deductions only
                        $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                        FROM deductions d
                        JOIN employees e ON d.employee_id = e.id
                        JOIN designation des ON e.designation_id = des.id
                        WHERE d.NSSF IS NOT NULL"; // Fetch only rows with NHIF deductions
                        $deductions = mysqli_query($conn, $query);
                        $advanceTotal = 0;
                        while ($advanceRow = mysqli_fetch_assoc($deductions)) {
                            $advanceTotal += $advanceRow['advance'];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($advanceRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($advanceRow['designation_name']); ?></td>
                                <td><?= number_format($advanceRow['advance'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($advanceTotal, 2); ?></td>
                                <!-- Total in the same column as NHIF figures -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        onclick="printContent('advanceContent')">Print</button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF('advance')">Download as
                        PDF</button>
                </div>
            </div>
        </div>
    </div>

    <!-- RENT Modal (Similar to Rent Modal) -->
    <div class="modal fade" id="payeModal6" tabindex="-1" role="dialog" aria-labelledby="payeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="rentContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="payeModalLabel">Rent Deductions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Rent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch NSSF deductions only
                        $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                        FROM deductions d
                        JOIN employees e ON d.employee_id = e.id
                        JOIN designation des ON e.designation_id = des.id
                        WHERE d.rent IS NOT NULL"; 
                        $deductions = mysqli_query($conn, $query);
                            $rentTotal = 0;
                            while ($rentRow = mysqli_fetch_assoc($deductions)) {
                                $rentTotal += $rentRow['rent'];
                                ?>
                            <tr>
                                <td><?= htmlspecialchars($rentRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($rentRow['designation_name']); ?></td>
                                <td><?= number_format($rentRow['rent'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($rentTssssssotal, 2); ?></td>
                                <!-- Total in the same column as NHIF figures -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary" onclick="printContent('rentContent')">Print</button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF('rent')">Download as PDF</button>
                </div>
            </div>
        </div>
    </div>
   <!-- Imarika Sacco Deductions Modal -->
<div class="modal fade" id="payeModal7" tabindex="-1" role="dialog" aria-labelledby="payeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="imarikasaccoContent">
            <div class="modal-header">
                <h5 class="modal-title" id="payeModalLabel">Imarika Sacco Deductions</h5>
                <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Imarika Sacco</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch imarika_sacco deductions only
                        $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                                  FROM deductions d
                                  JOIN employees e ON d.employee_id = e.id
                                  JOIN designation des ON e.designation_id = des.id
                                  WHERE d.imarika_sacco IS NOT NULL";
                        $deductions = mysqli_query($conn, $query);

                        $saccoTotal = 0; // Initialize $saccoTotal variable
                        while ($imarikaRow = mysqli_fetch_assoc($deductions)) {
                            $saccoTotal += $imarikaRow['imarika_sacco']; // Calculate total for Imarika Sacco
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($imarikaRow['employee_name']); ?></td>
                            <td><?= htmlspecialchars($imarikaRow['designation_name']); ?></td>
                            <td><?= number_format($imarikaRow['imarika_sacco'], 2); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <td></td> <!-- Empty cell to maintain column structure -->
                            <td><strong><?= number_format($saccoTotal, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
                <!-- Prepared/Approved Section -->
                <div class="prepared-section">
                    <p>PREPARED BY: <span class="input-field">Accounts Assistant Name</span></p>
                    <p>APPROVED BY: <span class="input-field">CHIEF PRINCIPAL / SECRETARY B.O.M.</span></p>
                    <p>PAYMENT BY: <span class="input-field">COLLEGE BURSAR</span></p>
                    <p>PAYMENT RECEIVED BY: IMARIKA SACCO SOCIETY LTD</p>
                </div>
            </div>
            <div class="modal-footer no-print"> <!-- Add class "no-print" -->
                <button type="button" class="btn btn-secondary no-print" data-dismiss="modal">Close</button>
                <!-- Print and Download Buttons -->
                <button type="button" class="btn btn-primary no-print" onclick="printContent('imarikasaccoContent')">Print</button>
                <button type="button" class="btn btn-success no-print" onclick="downloadPDF('imarikasacco')">Download as PDF</button>
            </div>
        </div>
    </div>
</div>

<!-- CSS to hide elements during print -->
<style>
    @media print {
        .no-print, .close {
            display: none !important;
        }
    }
</style>

<!-- JS Print and Download Functions -->
<script>
  function printContent(elementId) {
    var content = document.getElementById(elementId).innerHTML;
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print(); // Call the print method
    printWindow.close(); // Close the print window
}

    function downloadPDF(type) {
        var doc = new jsPDF();
        var content = document.getElementById(type + 'Content').innerHTML;
        doc.fromHTML(content, 15, 15, {
            'width': 170,
        });
        doc.save(type + '_Deductions.pdf');
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
