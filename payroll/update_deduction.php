<?php
include 'db.php';
// Check if the form was submitted
if (isset($_POST['update_deductions'])) {
    $employee_id = $_POST['employee_id'];
    $nssf = $_POST['NSSF'];
    $nhif = $_POST['NHIF'];
    $paye = $_POST['paye'];
    $affordable_housing_levy = $_POST['affordable_housing_levy'];
    $family_bank_loan = $_POST['family_bank_loan'];
    $welfare = $_POST['welfare'];
    $kudheiha = $_POST['kudheiha'];
    $advance = $_POST['advance'];
    $rent = $_POST['rent'];
    $imarika_sacco = $_POST['imarika_sacco'];

    // Update the deductions in the database
    $update_query = "UPDATE deductions SET 
        NSSF = '$nssf', 
        NHIF = '$nhif', 
        paye = '$paye', 
        affordable_housing_levy = '$affordable_housing_levy',
        family_bank_loan = '$family_bank_loan',
        welfare = '$welfare',
        kudheiha = '$kudheiha',
        advance = '$advance',
        rent = '$rent',
        imarika_sacco = '$imarika_sacco'
        WHERE employee_id = '$employee_id'";

    mysqli_query($conn, $update_query);

    // Redirect back to the deductions page
    header('Location: admin_dashboard.php');
    exit();
}