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
    <style>



    </style>
</head>

<body>
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
                                        <th><button class="btn btn-link" data-toggle="modal" data-target="#payeModal1"
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

    <!-- NSSF Modal (Similar to NSSF Modal) -->
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
                            WHERE d.NSSF IS NOT NULL"; // Fetch only rows with NHIF deductions
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
                                <td><strong><?= number_format($nhifTotal, 2); ?></td>
                                <!-- Total in the same column as NHIF figures -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary" onclick="printContent('NSSFContent')">Print</button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF('NSSF')">Download as PDF</button>
                </div>
            </div>
        </div>
    </div>
    <!-- PAYE Modal (Similar to PAYE Modal) -->
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
                            // Fetch NSSF deductions only
                            $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                            FROM deductions d
                            JOIN employees e ON d.employee_id = e.id
                            JOIN designation des ON e.designation_id = des.id
                            WHERE d.NSSF IS NOT NULL"; // Fetch only rows with NHIF deductions
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
                                <td><strong><?= number_format($nhifTotal, 2); ?></td>
                                <!-- Total in the same column as NHIF figures -->
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary" onclick="printContent('payeContent')">Print</button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF('paye')">Download as PDF</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PAYE Modal (Similar to Affordable Housing Modal) -->
    <div class="modal fade" id="payeModal1" tabindex="-1" role="dialog" aria-labelledby="payeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="affordablehousinglevyfContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="payeModalLabel">Affordable Housing Deductions</h5>
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
                             // Fetch NSSF deductions only
                             $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                             FROM deductions d
                             JOIN employees e ON d.employee_id = e.id
                             JOIN designation des ON e.designation_id = des.id
                             WHERE d.NSSF IS NOT NULL"; // Fetch only rows with NHIF deductions
                             $deductions = mysqli_query($conn, $query);
                            while ($payeRow = mysqli_fetch_assoc($deductions)) {
                                $payeTotal += $payeRow['affordable_housing_levy'];
                                ?>
                            <tr>
                                <td><?= htmlspecialchars($payeRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($payeRow['designation_name']); ?></td>
                                <td><?= number_format($payeRow['affordable_housing_levy'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($nhifTotal, 2); ?></td>
                                <!-- Total in the same column as NHIF figures -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Print and Download Buttons -->
                    <button type="button" class="btn btn-primary"
                        onclick="printContent('affordablehousinglevyfContent')">Print</button>
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
                            $payeTotal = 0;
                            while ($payeRow = mysqli_fetch_assoc($deductions)) {
                                $payeTotal += $payeRow['family_bank_loan'];
                                ?>
                            <tr>
                                <td><?= htmlspecialchars($payeRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($payeRow['designation_name']); ?></td>
                                <td><?= number_format($payeRow['family_bank_loan'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($nhifTotal, 2); ?></td>
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
                            $payeTotal = 0;
                            while ($payeRow = mysqli_fetch_assoc($deductions)) {
                                $payeTotal += $payeRow['welfare'];
                                ?>
                            <tr>
                                <td><?= htmlspecialchars($payeRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($payeRow['designation_name']); ?></td>
                                <td><?= number_format($payeRow['welfare'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($nhifTotal, 2); ?></td>
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
                            $payeTotal = 0;
                            while ($payeRow = mysqli_fetch_assoc($deductions)) {
                                $payeTotal += $payeRow['kudheiha'];
                                ?>
                            <tr>
                                <td><?= htmlspecialchars($payeRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($payeRow['designation_name']); ?></td>
                                <td><?= number_format($payeRow['kudheiha'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($nhifTotal, 2); ?></td>
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
                        $payeTotal = 0;
                        while ($payeRow = mysqli_fetch_assoc($deductions)) {
                            $payeTotal += $payeRow['advance'];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($payeRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($payeRow['designation_name']); ?></td>
                                <td><?= number_format($payeRow['advance'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($nhifTotal, 2); ?></td>
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
                        WHERE d.NSSF IS NOT NULL"; // Fetch only rows with NHIF deductions
                        $deductions = mysqli_query($conn, $query);
                            $payeTotal = 0;
                            while ($payeRow = mysqli_fetch_assoc($deductions)) {
                                $payeTotal += $payeRow['rent'];
                                ?>
                            <tr>
                                <td><?= htmlspecialchars($payeRow['employee_name']); ?></td>
                                <td><?= htmlspecialchars($payeRow['designation_name']); ?></td>
                                <td><?= number_format($payeRow['rent'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th>Total</th>
                                <td></td> <!-- Empty cell to maintain column structure -->
                                <td><strong><?= number_format($nhifTotal, 2); ?></td>
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
                           // Fetch NSSF deductions only
                    $query = "SELECT d.*, e.name AS employee_name, des.designation_name AS designation_name 
                    FROM deductions d
                    JOIN employees e ON d.employee_id = e.id
                    JOIN designation des ON e.designation_id = des.id
                    WHERE d.NSSF IS NOT NULL"; // Fetch only rows with NHIF deductions
                    $deductions = mysqli_query($conn, $query);
                        $payeTotal = 0;
                        while ($payeRow = mysqli_fetch_assoc($deductions)) {
                            $payeTotal += $payeRow['imarika_sacco'];
                            ?>
                        <tr>
                            <td><?= htmlspecialchars($payeRow['employee_name']); ?></td>
                            <td><?= htmlspecialchars($payeRow['designation_name']); ?></td>
                            <td><?= number_format($payeRow['imarika_sacco'], 2); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>Total</th>
                            <td></td> <!-- Empty cell to maintain column structure -->
                            <td><strong><?= number_format($nhifTotal, 2); ?></td>
                            <!-- Total in the same column as NHIF figures -->
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
