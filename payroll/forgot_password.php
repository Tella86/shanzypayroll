<?php
include('db.php');
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Create a unique token
        $token = bin2hex(random_bytes(50));
        
        // Save token in the database with an expiration time (optional)
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expiration = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Create reset password link
        $resetLink = "http://localhost/shanzypayroll\payroll/reset_password.php?token=" . $token;
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'mail.ezems.co.ke';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@ezems.co.ke'; // SMTP username
            $mail->Password = '@ezems2023/*-'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            //Recipients
            $mail->setFrom('info@ezems.co.ke', 'Ezems Tech');
            $mail->addAddress($email); // Recipient's email

            //Content
            $mail->isHTML(true);
            $mail->Subject = "Password Reset Request";
            $mail->Body    = "Click on the following link to reset your password: <a href='" . $resetLink . "'>Reset Password</a>";
            
            // Send the email
            $mail->send();
            echo "<script>
                alert('A password reset link has been sent to your email address.!');
               
              </script>";
           
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Failed to send email. Please try again later.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No user found with this email address.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">Forgot Password</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
</body>
</html>
