<?php
session_start();
include('db.php');

// Retrieve form data
$employee_id = $_POST['employee_id'];
$nssf = $_POST['nssf'];
$nhif = $_POST['nhif'];
$paye = $_POST['paye'];  // PAYE will be calculated in your application layer
$affordable_housing_levy = $_POST['affordable_housing_levy'];
$family_bank_loan = $_POST['family_bank_loan'];
$welfare = $_POST['welfare'];
$kudheiha = $_POST['kudheiha'];
$advance = $_POST['advance'];
$rent = $_POST['rent'];
$imarika_sacco = $_POST['imarika_sacco'];

// Insert data into the deductions table
$sql = "INSERT INTO deductions (employee_id, nssf, nhif, paye, affordable_housing_levy, family_bank_loan, welfare, kudheiha, advance, rent, imarika_sacco)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("idddddddddd", $employee_id, $nssf, $nhif, $paye, $affordable_housing_levy, $family_bank_loan, $welfare, $kudheiha, $advance, $rent, $imarika_sacco);

if ($stmt->execute()) {
    echo "Deductions submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>