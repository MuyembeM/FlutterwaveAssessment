<?php
    // start new PHP session
    session_start();
    // set default timezone
    date_default_timezone_set('Africa/Lusaka');

    // reset session variable
    //$_SESSION['poll'] = null;
    
    try{
        // Query Flutterwave account for transfer id
        $url = 'https://api.flutterwave.com/v3/transfers';

        $curl = curl_init();

        curl_setopt_array($curl,array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n
                \"account_bank\" : \"MPS\",\n
                \"account_number\" : \"2540782773934\",\n
                \"amount\" : 50, \n
                \"narration\" : \"New trasfer\",\n
                \"currency\" : \"KES\",\n
                \"reference\" : \"mk-902837-jqzc\",\n
                \"beneficiary_name\" : \"Mike Muyembe\"\n
            }",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer FLWSECK_TEST-XXXXXXXXXXXXXXXXXXXX-X",
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
    
        // set variable to store id
        $id = 0;

        // loop through result and get id
        foreach ($response as $key=>$value){
            if ($key == 'data'){
                foreach ($value as $key1=>$value1){
                    if ($key1 == 'id'){
                        $id = $value1;
                    }
                }
            }
        }
        
        // set session variable to keep track of re-query times
        if (!isset($_SESSION['poll'])){
            $_SESSION['poll'] = time();
        }
        $time_to_poll = $_SESSION['poll'] - time();
        
        // set variable to keep track of final status
        $completed = false;
        
        // re-query Flutterwave account for status every 30 seconds until transfer completes
        while ($completed == false){
            if ($time_to_poll <= 0){
                $url = 'https://api.flutterwave.com/v3/transfers/' . $id;
                $curl = curl_init();

                curl_setopt_array($curl,array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Authorization: Bearer FLWSECK_TEST-096df3a0517dfbc63f167c0bc2e782c7-X",
                    ),
                ));

                $response = json_decode(curl_exec($curl));
                curl_close($curl);
                
                $status = $complete_message = '';

                // loop through result and get status and complete_message
                foreach ($response as $key=>$value){
                    if ($key == 'data'){
                        foreach ($value as $key1=>$value1){
                            if ($key1 == 'status'){
                                $status = $value1;
                            }
                            if ($key1 == 'complete_message'){
                                $complete_message = $value1;
                            }
                        }
                    }
                }

                if ($complete_message != ''){
                    // display final message and stop polling
                    echo "Complete Message: " . $complete_message;
                    $completed = true;
                    $_SESSION['poll'] = null;
                }else{
                    //increase time to next poll by 30 seconds
                    $_SESSION['poll'] = time() + 30;                  
                }
            }
            $time_to_poll = $_SESSION['poll'] - time();
        }    
    }catch(exception $e){
        echo $e->getMessage();
    }
?>
