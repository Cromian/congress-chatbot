<?php
// Libraries
include ('lib/bitly.php');
include ('lib/key.php');

// show data for just the bot
function sendMessage($parameters) {
    
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if (strstr($ua, 'Apache-HttpClient')) {
        echo json_encode($parameters);        
    }
}

// Basic cURL call to get content. 
function getData($url){
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); 
    $result = curl_exec( $ch );
    curl_close($ch);
    
    return $result;
}

// Global Var
$session = '115';
$message = null;
$display_text = null;

// Parse Incoming Data from api.ai 
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);

// If action is [bills_gen].
if ($update['result']['action'] === 'bills_gen') 
{
    include ('inc/_bills-gen.php');
}

// if action is [bill_specific].
if ($update['result']['action'] === 'bill_specific')
{
    include ('inc/_bills-specific.php');
}

// if action is [get_member].
if ($update['result']['action'] === 'get_member') 
{
    include ('inc/_get-member.php');
}

// Send Response
sendMessage(array(
    "source" => $update["result"]["source"],
    "speech" => $message,
    "displayText" => $display_text,
    "contextOut" => array()
));

?>