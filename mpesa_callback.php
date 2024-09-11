<?php
include 'db.php';

// Safaricom will POST payment data to this file
$mpesaResponse = file_get_contents('php://input');
$response = json_decode($mpesaResponse, true);

if ($response['Body']['stkCallback']['ResultCode'] == 0) {
    // Payment successful
    $mpesaReceiptNumber = $response['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
    $amount = $response['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
    $phoneNumber = $response['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];

    // Find the pending payment record in your database (you can also use Transaction ID, etc.)
    $query = "UPDATE payments SET payment_status = 'Approved', mpesa_receipt = '$mpesaReceiptNumber' WHERE phone_number = '$phoneNumber' AND amount_paid = '$amount'";
    mysqli_query($conn, $query);

    // Send confirmation to the user (optional)
}
?>
