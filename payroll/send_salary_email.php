<?php
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
include('db.php');

if (isset($_GET['payroll_id'])) {
    $payroll_id = $_GET['payroll_id'];
    
    // Fetch payroll and employee data
    $query = mysqli_query($conn, "SELECT p.*, e.name, e.email FROM payroll p JOIN employees e ON p.employee_id = e.id WHERE p.id = '$payroll_id'");
    $payroll = mysqli_fetch_assoc($query);

 
    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
       $mail->isSMTP();
        $mail->Host = 'mail.ezems.co.ke';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@ezems.co.ke'; // SMTP username
        $mail->Password = '@ezems2023/*-'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        //Recipients
        $mail->setFrom('info@ezems.co.ke', 'Payroll System');
        $mail->addAddress($payroll['email'], $payroll['name']);

        // Attach PDF
        $mail->addAttachment('path_to_salary_pdf/salary_slip_' . $payroll['name'] . '.pdf');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Salary Slip for ' . $payroll['month'];
        $mail->Body    = 'Dear ' . $payroll['name'] . ',<br><br>Your salary slip for the month of ' . $payroll['month'] . ' is attached.<br><br>Best Regards,<br>Payroll Team';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

