<?php
require('../fpdf/fpdf.php');
include('db.php');
include('phpqrcode/qrlib.php'); // Include the QR code library

if (!isset($_GET['employee_id'], $_GET['month'])) die('Employee ID and month required');

$employee_id = $_GET['employee_id'];
$month = $_GET['month'];

// Fetch employee details
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param('i', $employee_id);
$stmt->execute();
$employee = $stmt->get_result()->fetch_assoc();
if (!$employee) die('Employee not found');

// Fetch payroll record for the employee for the selected month
$stmt = $conn->prepare("SELECT * FROM payroll WHERE employee_id = ? AND month = ?");
$stmt->bind_param('is', $employee_id, $month);
$stmt->execute();
$payroll = $stmt->get_result()->fetch_assoc();
if (!$payroll) die('Payroll record not found for this employee and month');

// Fetch deductions for the employee
$stmt = $conn->prepare("SELECT * FROM deductions WHERE employee_id = ?");
$stmt->bind_param('i', $employee_id);
$stmt->execute();
$deductions = $stmt->get_result()->fetch_assoc();
if (!$deductions) die('Deductions record not found for this employee and month');

// Generate the QR code content (employee PIN and net salary)
$qrContent = "Employee PIN: " . $employee['pin_no'] . "\nNet Salary: " . number_format($payroll['net_salary'], 2);
$qrFilePath = 'temp_qrcode.png'; // Temporary file path for QR code
QRcode::png($qrContent, $qrFilePath, QR_ECLEVEL_L, 3);// Generate the QR code with low error correction

// Generate PDF
$pdf = new FPDF('P', 'mm', array(114, 162)); // C6 envelope size
$pdf->AddPage();
// Add Logo
$pdf->Image('imgs/logo.jpg', 16, 13, 11);
$pdf->SetFont('Helvetica', 'B', 7);
$pdf->Cell(0, 3, 'SHANZU TEACHERS TRAINING COLLEGE - MOMBASA', 0, 1, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(0, 3, 'BOARD OF MANAGEMENT EMPLOYEES', 0, 1, 'C');
$pdf->Ln(2);

// Pay Slip Month
$pdf->SetFont('Helvetica', 'B', 7);
$pdf->Cell(0, 5, strtoupper($month) . ' PAY SLIP', 0, 1, 'C');
$pdf->Ln(3);

// Employee details
$pdf->SetFont('Helvetica', '', 7);
$pdf->Cell(30, 5, 'Name:', 0, 0);
$pdf->Cell(50, 5, $employee['name'], 0, 1);
$pdf->Cell(30, 5, 'Employer\'s PIN NO:', 0, 0);
$pdf->Cell(50, 5, 'P051102491S', 0, 1);
$pdf->Cell(30, 5, 'Employee\'s PIN NO:', 0, 0);
$pdf->Cell(50, 5, $employee['pin_no'], 0, 1);
$pdf->Ln(2);

// Earnings Section
$pdf->SetFont('Helvetica', '', 5);
$pdf->Cell(40, 5, 'Basic Salary:', 1);
$pdf->Cell(30, 5, number_format($payroll['basic_salary'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'House Allowance:', 1);
$pdf->Cell(30, 5, number_format($payroll['house_allowance_cluster2'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'Medical Allowance:', 1);
$pdf->Cell(30, 5, number_format($payroll['medical_allowance'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'Commuter Allowance:', 1);
$pdf->Cell(30, 5, number_format($payroll['commuter_allowance'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'Leave Allowance:', 1);
$pdf->Cell(30, 5, number_format($payroll['leave_allowance'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'Affordable Housing Levy:', 1);
$pdf->Cell(30, 5, number_format($payroll['affordable_housing_levy'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'NSSF:', 1);
$pdf->Cell(30, 5, number_format($payroll['nssf'], 2), 1, 1, 'R');
$pdf->SetFont('Helvetica', 'B', 6);
$pdf->Cell(40, 5, 'Gross Salary:', 1);
$pdf->Cell(30, 5, number_format($payroll['gross_salary'], 2), 1, 1, 'R');
$pdf->Ln(1);

// Deductions Section
$pdf->SetFont('Helvetica', 'B', 7);
$pdf->Cell(0, 3, 'DEDUCTIONS', 0, 1);
$pdf->SetFont('Helvetica', '', 5);
$pdf->Cell(40, 5, 'N.S.S.F:', 1);
$pdf->Cell(30, 5, number_format($deductions['NSSF'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'Affordable Housing Levy:', 1);
$pdf->Cell(30, 5, number_format($deductions['affordable_housing_levy'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'P.A.Y.E.:', 1);
$pdf->Cell(30, 5, number_format($deductions['paye'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'Family Bank Loan:', 1);
$pdf->Cell(30, 5, number_format($deductions['family_bank_loan'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'Welfare:', 1);
$pdf->Cell(30, 5, number_format($deductions['welfare'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'N.H.I.F.:', 1);
$pdf->Cell(30, 5, number_format($deductions['NHIF'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'Rent:', 1);
$pdf->Cell(30, 5, number_format($deductions['rent'], 2), 1, 1, 'R');
$pdf->Cell(40, 5, 'Imarika Sacco:', 1);
$pdf->Cell(30, 5, number_format($deductions['imarika_sacco'], 2), 1, 1, 'R');
$pdf->SetFont('Helvetica', 'B', 6);
$pdf->Cell(40, 5, 'Total Deductions:', 1);
$pdf->Cell(30, 5, number_format($deductions['total_deductions'], 2), 1, 1, 'R');

// Net Pay Section
$pdf->SetFont('Helvetica', 'B', 7);
$pdf->Cell(40, 5, 'NET PAY:', 1);
$pdf->Cell(30, 5, number_format($payroll['net_salary'], 2), 1, 1, 'R');
$pdf->Ln(1);
// Add QR code at the bottom
$pdf->Image($qrFilePath, ($pdf->GetPageWidth() - 20) / 2, $pdf->GetY(), 20, 20); // Centered QR code with a size of 20x20mm

// Clean up the QR code image file after use
unlink($qrFilePath);

// Output PDF
$pdf->Output();
?>
