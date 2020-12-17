<?php

    try{
        
        $url = 'https://api.flutterwave.com/v3/bills';

        $curl = curl_init();

        curl_setopt_array($curl,array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n        
                \"country\": \"NG\",\n        
                \"customer\": \"+23490803840303\",\n        
                \"amount\": 500,\n        
                \"recurrence\": \"WEEKLY\",\n        
                \"type\": \"AIRTIME\",\n        
                \"reference\": \"930049200921\"\n
            }",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer FLWSECK_TEST-XXXXXXXXXXXXXXXXXXXXXXXXXXXXX-X",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        echo $response;

    }catch(exception $e){
        echo $e->getMessage();
    }

?>
