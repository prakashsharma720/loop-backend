<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function sendSMS($recipient_no, $message){
    // Request parameters array
    $requestParams = array(
        'user' => 'codexworld',
        'apiKey' => '262142AbJ0kteypc5c5fbb5d',
        'senderID' => 'PRAKASH',
        'recipient_no' => $recipient_no,
		'unicode' => '1',
        'message' => $message
    );
    
    // Merge API url and parameters
    $apiUrl = "http://api.msg91.com/api/sendhttp.php?";
    foreach($requestParams as $key => $val){
        $apiUrl .= $key.'='.urlencode($val).'&';
    }
    $apiUrl = rtrim($apiUrl, "&");
    
    // API call
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    // Return curl response
    return $response;
}

?>