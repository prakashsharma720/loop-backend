<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); 


    function sendsms($number, $message_body, $return = '0'){       
        $sender = 'Loop Newsletter';  //Use sender name here
        $smsGatewayUrl = 'http://cloud.smsindiahub.in/api/mt/SendSMS?user=demo&password=demo123&senderid=WEBSMS&channel=Promo&DCS=0&flashsms=0&number=91989xxxxxxx&text=test message&route=##&PEId=##';
        $apikey = '2hL6XSUCl06kTQjIUyIRiQ'; // Use API key here

        // $message = urlencode($message);
        $api_element = '/api/web/send/';
        $api_params = $api_element.'?apikey='.$apikey.'&sender='.$sender.'&to='.$mobile.'&message='.$message;    
        $smsgatewaydata = $smsGatewayUrl.$api_params;
        $url = $smsgatewaydata;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        if($return == '1'){
            return $output;            
        }
    }

?>