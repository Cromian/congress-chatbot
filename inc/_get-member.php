<?php
// Define variables
$zipcode = $update["result"]["parameters"]["zip-code"];
$getmember = $update["result"]["parameters"]["get_member"];

// Get result array and run shift & reset.
$get_results = file_get_contents('http://whoismyrepresentative.com/getall_mems.php?zip='. $zipcode .'&output=json');
$rep_array = json_decode($get_results, true);
$rep_data = array($rep_array);
$rep_data = reset($rep_data);
$rep_data = array_shift($rep_data);

// Prevent cross geo by filtering out border districts by getting state value from senators. 
$verifty_state = end($rep_data); 

$return_rep = '';

// If zipcode is DC
if (($zipcode >= 20001) && ($zipcode <= 20456)) {

    $return_rep = 'Rep. Eleanor Holmes Norton';

}
else {

    if ($getmember === 'senator') 
    {
        $senate_array = array_slice($rep_data, -2, 2, true);
        foreach ($senate_array as $sen) {
            $return_rep .= $sen['name'] . ', ';
        }
    }
    else
    {
        $i = 0;
        $len = count($rep_data);
        foreach ($rep_data as $rep) {
    
            if ($rep['state'] === $verifty_state['state']) 
            {
                // Determine if Rep. or Sen by office location. 
                if (strpos(strtolower($rep['office']), 'senate') !== false)
                {
                    $rep_type = '(Sen.)';
                }
                else
                {
                    $rep_type = '(Rep.)';
                }
    
                $return_rep .= $rep['name'] . ' ' . $rep_type . ', ';
            }
            $i++;   
        }
    }


}





$message = $return_rep ;
$display_text = 'Webhook getMember';

?>