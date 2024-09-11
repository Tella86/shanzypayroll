<?php
include 'mpesa_config.php';

function generateMpesaToken() {
    $url = ENV == "sandbox" 
        ? "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" 
        : "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

    $credentials = base64_encode(CONSUMER_KEY . ":" . CONSUMER_SECRET);
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $credentials));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($curl);
    $result = json_decode($response);
    
    return $result->access_token;
}
function lipaNaMpesaOnline($phone, $amount) {
    $accessToken = generateMpesaToken();
    
    $timestamp = date('YmdHis');
    $password = base64_encode(SHORTCODE . PASSKEY . $timestamp);
    
    $url = ENV == "sandbox"
        ? "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest"
        : "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json"
    ));
    
    $postData = array(
        "BusinessShortCode" => SHORTCODE,
        "Password" => $password,
        "Timestamp" => $timestamp,
        "TransactionType" => TRANSACTION_TYPE,
        "Amount" => $amount,
        "PartyA" => $phone,  // The phone number initiating the transaction
        "PartyB" => SHORTCODE,
        "PhoneNumber" => $phone,
        "CallBackURL" => CALLBACK_URL,
        "AccountReference" => ACCOUNT_REFERENCE,
        "TransactionDesc" => TRANSACTION_DESC
    );
    
    $data_string = json_encode($postData);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response);
}
