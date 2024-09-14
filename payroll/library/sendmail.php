<?php
/**
 * Sets error header and JSON error message response.
 *
 * @param  string $message Error message of the response
 * @return void
 */
function errorResponse($message) {
    header('Content-Type: application/json');
    header('HTTP/1.1 500 Internal Server Error');
    die(json_encode(array('message' => $message)));
}

/**
 * Pulls posted values for all fields in $fields_req array.
 * If a required field does not have a value, an error response is given.
 *
 * @return string Message body constructed from form input
 */
function constructMessageBody() {
    $fields_req = array("fname" => true, "lname" => true, "email" => true, "comment" => true);
    $message_body = "";

    foreach ($fields_req as $name => $required) {
        $postedValue = isset($_POST[$name]) ? trim($_POST[$name]) : '';
        if ($required && empty($postedValue)) {
            errorResponse("$name is required.");
        } else {
            $message_body .= ucfirst($name) . ":  " . htmlspecialchars($postedValue) . "\n";
        }
    }
    return $message_body;
}

header('Content-Type: application/json');

// Perform reCAPTCHA verification to prevent spam
$captcha_url = 'https://www.google.com/recaptcha/api/siteverify';
$captcha_post_data = http_build_query(array(
    'secret' => getenv('RECAPTCHA_SECRET_KEY'), 
    'response' => $_POST["g-recaptcha-response"]
));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $captcha_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $captcha_post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$captcha_result = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!$captcha_result['success']) {
    errorResponse('reCAPTCHA check failed! Error codes: ' . join(', ', $captcha_result['error-codes']));
}

// Construct the message body
$messageBody = constructMessageBody();

// Include PHPMailer and send the email
require './vendor/phpmailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->CharSet = 'UTF-8';
$mail->isSMTP();
$mail->Host = getenv('FEEDBACK_HOSTNAME');

if (!getenv('FEEDBACK_SKIP_AUTH')) {
    $mail->SMTPAuth = true;
    $mail->Username = getenv('FEEDBACK_EMAIL');
    $mail->Password = getenv('FEEDBACK_PASSWORD');
}

$encryption = getenv('FEEDBACK_ENCRYPTION');
if ($encryption == 'TLS') {
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
} elseif ($encryption == 'SSL') {
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
}

// Set email sender and recipient
$mail->setFrom($_POST['email'], $_POST['fname'] . ' ' . $_POST['lname']);
$mail->addAddress(getenv('FEEDBACK_EMAIL')); // The email to receive the form submission

// Set email subject and body
$mail->Subject = 'Contact Form Submission: ' . $_POST['fname'] . ' ' . $_POST['lname'];
$mail->Body = $messageBody;

// Attempt to send the email
if ($mail->send()) {
    echo json_encode(array('message' => 'Your message was successfully submitted.'));
} else {
    errorResponse('An error occurred while attempting to send the email: ' . $mail->ErrorInfo);
}
?>
