<?php
require 'db.php'; // Ensure this file sets up your PDO connection
require 'africastalking/src/AfricasTalking.php';
require 'africastalking/vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;

// AfricasTalking API credentials
$username = 'ezems';
$apiKey = '39fafb4f99370b33f2ce8a89fb49de56c6db75d19219d49db45c0522931be77e';

$AT = new AfricasTalking($username, $apiKey);

// Get POST data (the message and recipients)
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->message) || !isset($data->recipients)) {
    echo json_encode(['success' => false, 'message' => 'No message or recipients provided']);
    exit;
}

$message = $data->message;
$recipients = $data->recipients;

// If sending to all, fetch all member phone numbers from the database
if (in_array('all', $recipients)) {
    try {
        $query = "SELECT name, phone FROM employees"; // Fetching for all employees
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Build the recipient list
        $recipients = [];
        foreach ($employees as $employee) {
            $recipients[] = [
                'name' => $employee['name'],
                'phone' => $employee['phone']
            ];
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Send SMS to each recipient
try {
    $sms = $AT->sms();

    foreach ($recipients as $recipient) {
        if (is_array($recipient)) {
            $name = $recipient['name'];           // Employee name
            $phoneNumber = $recipient['phone'];   // Employee phone number

            // Create the personalized message
            $personalizedMessage = "Dear $name,\n\n" . $message;

            // Send the message
            $response = $sms->send([
                'to'      => $phoneNumber,
                'message' => $personalizedMessage
            ]);
        } else {
            // Handle manual phone numbers
            $phoneNumber = $recipient; // Assuming this is a manual number

            // Send the message
            $response = $sms->send([
                'to'      => $phoneNumber,
                'message' => $message
            ]);
        }
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>