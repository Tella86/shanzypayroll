<?php
  /**
   * Sets error header and json error message response.
   *
   * @param  String $messsage error message of response
   * @return void
   */
  function errorResponse ($messsage) {
    header('HTTP/1.1 500 Internal Server Error');
    die(json_encode(array('message' => $messsage)));
  }

  /**
   * Pulls posted values for all fields in $fields_req array.
   * If a required field does not have a value, an error response is given.
   */
  function constructMessageBody () {
    $fields_req =  array("name" => true, "email" => true, "message" => true);
    $message_body = "";
    foreach ($fields_req as $name => $required) {
      $postedValue = $_POST[$name];
      if ($required && empty($postedValue)) {
        errorResponse("$name is empty.");
      } else {
        $message_body .= ucfirst($name) . ":  " . $postedValue . "\n";
      }
    }
    return $message_body;
  }

  header('Content-type: application/json');

  //do Captcha check, make sure the submitter is not a robot:)...
  $captcha_url = 'https://www.google.com/recaptcha/api/siteverify';
  $captcha_header = 'Content-type: application/x-www-form-urlencoded';
  $captcha_post_data = http_build_query(array('secret' => getenv('RECAPTCHA_SECRET_KEY'), 'response' => $_POST["g-recaptcha-response"]));
  // prefer cURL over #file_get_contents...
  if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $captcha_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($captcha_header));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $captcha_post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = json_decode(curl_exec($ch));
    curl_close($ch);
  } else {
    $opts = array('http' =>
      array(
        'method'  => 'POST',
        'header'  => $captcha_header,
        'content' => $captcha_post_data
      )
    );
    $context  = stream_context_create($opts);
    $result = json_decode(file_get_contents($captcha_url, false, $context, -1, 40000));
  }


  if (!$result->success) {
    errorResponse('reCAPTCHA checked failed! Error codes: ' . join(', ', $result->{"error-codes"}));
  }
  //attempt to send email
  $messageBody = constructMessageBody();
  require './vender/php_mailer/PHPMailerAutoload.php';
  $mail = new PHPMailer;
  $mail->CharSet = 'UTF-8';
  $mail->isSMTP();
  $mail->Host = getEnv('FEEDBACK_HOSTNAME');
  if (!getenv('FEEDBACK_SKIP_AUTH')) {
    $mail->SMTPAuth = true;
    $mail->Username = getenv('FEEDBACK_EMAIL');
    $mail->Password = getenv('FEEDBACK_PASSWORD');
  }
  if (getenv('FEEDBACK_ENCRYPTION') == 'TLS') {
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
  } elseif (getenv('FEEDBACK_ENCRYPTION') == 'SSL') {
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
  }

  $mail->Sender = getenv('FEEDBACK_EMAIL');
  $mail->setFrom($_POST['email'], $_POST['name']);
  $mail->addAddress(getenv('FEEDBACK_EMAIL'));

  $mail->Subject = $_POST['reason'];
  $mail->Body  = $messageBody;


  //try to send the message
  if($mail->send()) {
    echo json_encode(array('message' => 'Your message was successfully submitted.'));
  } else {
    errorResponse('An unexpected error occured while attempting to send the email: ' . $mail->ErrorInfo);
  }
?>
