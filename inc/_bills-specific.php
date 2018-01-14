<?php

// Function to parse congress API.
function parseAPI($url) {
    global $pro_publica_key;
    
    $ch = curl_init();
    $header = array('X-API-Key: '. $pro_publica_key .'');
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header ); 
    $result = curl_exec( $ch );
    
    curl_close($ch);
    return $result;
}

// variables
$api_base_url = 'https://api.propublica.org/congress/v1/';
$congress_gov_base_url = 'https://www.congress.gov/bill/';

// Values from dialogflow
$bill_code = strtolower($update["result"]["parameters"]['bill_codes']);
$bill_num =  strtolower($update["result"]["parameters"]['number']);
$bill_action = strtolower($update["result"]["parameters"]['action']);

// Get ProPublica Data:
$pro_publica_bill_id = $bill_code . $bill_num;
$bill_endpoint_url = $api_base_url . $session . '/bills/' . $pro_publica_bill_id . '.json';

// Bill info from ProPublica
$json_output = json_decode(parseAPI($bill_endpoint_url), true);
$bill_array = array($json_output);
$bill_array = reset($bill_array);

// If return code of status, return last major action. 
if ($bill_action === 'status')
{
    $result_msg = $bill_array['results'][0]['latest_major_action'];
}

// Look at $bill_code and match to congress.gov url path.
switch ($bill_code) {
    
    case 'hr':
        $bill_type_path = 'house-bill';
    break;

    case 's':
        $bill_type_path = 'senate-bill';
    break;
    
    case 'hres':
        $bill_type_path = 'house-resolution';
    break;

    case 'sres':
        $bill_type_path = 'senate-resolution';
    break;

    case 'hjres':
        $bill_type_path = 'house-joint-resolution';
    break;

    case 'sjres':
        $bill_type_path = 'senate-joint-resolution';
    break;

    case 'hconres':
        $bill_type_path = 'house-concurrent-resolution';
    break;

    case 'sconres':
        $bill_type_path = 'senate-concurrent-resolution';
    break;

}

$bill_url_long = $congress_gov_base_url . $session .'-th-congress/' . $bill_type_path . '/' . $bill_num . '/text';

// shorten url
$params = array();
$params['access_token'] = $bitly_key;
$params['longUrl'] = $bill_url_long;
$results = bitly_get('shorten', $params);
$result_url = $results['data']['url'];

$message = $result_msg . ' More info: ' . $result_url ;
$display_text = 'Webhook getBillSpecific';

?>