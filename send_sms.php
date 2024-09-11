<?php
require_once 'vendor/autoload.php';

use Twilio\Rest\Client;

function sendSMS($to, $message) {
    $sid = 'your_twilio_sid';
    $token = 'your_twilio_auth_token';
    $twilio_number = 'your_twilio_number';
    
    $client = new Client($sid, $token);
    $client->messages->create(
        $to, 
        array(
            'from' => $twilio_number,
            'body' => $message
        )
    );
}

// Example of sending SMS to a parent after marks are entered
sendSMS('+1234567890', 'Your child has scored 85 in Math. View the full report on the portal.');
?>
