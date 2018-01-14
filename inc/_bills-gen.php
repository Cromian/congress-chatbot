<?php

// Default Var
$chamber = strtolower($update["result"]["parameters"]['chamber']);
$bill_status = strtolower($update["result"]["parameters"]['bill_status']);

// Declare chamber origin.
if (isset($chamber)) {
    $parm = '%22%2C%22chamber%22%3A%22';

    switch ($chamber) {

        case 'house':
        
            $origin_chamber = $parm . 'House';
    
        break;
    
        case 'senate':
    
            $origin_chamber = $parm . 'Senate';
    
        break;
    
        case 'congress':
    
        $origin_chamber = '';
    
        break;
        
    }
} else {

    $origin_chamber = '';

}

// Identify requested status.
if (isset($bill_status)) {

    if ($bill_status === 'passed') {

        $status = 'passed-both';
    
    }
    else {
        $status = 'introduced';

    }

}

$return_url = 'https://www.congress.gov/search?q=%7B%22source%22%3A%22legislation%22%2C%22congress%22%3A%22'. $session . $origin_chamber .'%22%2C%22bill-status%22%3A%22'. $status .'%22%7D';

// Function to shortin the link.
$params = array();
$params['access_token'] = $bitly_key;
$params['longUrl'] = $return_url;
$results = bitly_get('shorten', $params);
$result_url = $results['data']['url'];

$phrases = array(
    "Here you go!",
    "This is what I found.",
    "Looks like there is something.",
    "Yes. Follow this link."
);
$response = $phrases[mt_rand(0, count($phrases) - 1)];

$message = $response . ' ' . $result_url; 
$display_text = 'webhookGeo';

?>