<?php
    try{
        // get transactions from flutterwave using curl
        $url = 'https://api.flutterwave.com/v3/transactions';
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

        // initialize transaction variables
        $txref = $flwref = $chargemessage = $authmodel = $ip = $status = $chargetype = $custphone = 
        $custnetworkprovider = $currency = $custname = $custemail = $merchantname = $acctcountry = '';   
        $txid = $merchantid = $customerid = $chargecode = $amount = 0; 
        $createddate = null;

        // loop through result and populate variables
        foreach ($response as $key=>$values){
            if ($key == 'data'){
                foreach ($values as $value){
                    foreach ($value as $key1=>$value1){
                        if ($key1 == 'id'){
                            $txid = $value1;
                        }
                        if ($key1 == 'tx_ref'){
                            $txref = $value1;
                        }
                        if ($key1 == 'flw_ref'){
                            $flwref = $value1;
                        }
                        if ($key1 == 'amount'){
                            $amount = $value1;
                        }
                        if ($key1 == 'currency'){
                            $currency = $value1;
                        }
                        if ($key1 == 'processor_response'){
                            $chargemessage = $value1;
                        }
                        if ($key1 == 'status'){
                            $status = $value1;
                        }
                        if ($key1 == 'auth_model'){
                            $authmodel = $value1;
                        }
                        if ($key1 == 'ip'){
                            $ip = $value1;
                        }
                        if ($key1 == 'created_at'){
                            $createddate = $value1;
                        }
                        if ($key1 == 'payment_type'){
                            $chargetype = $value1;
                        }
                        if ($key1 == 'customer'){
                            foreach ($value1 as $key2=>$value2){
                                if ($key2 == 'email'){
                                    $custemail = $value2;
                                }
                                if ($key2 == 'phone_number'){
                                    $custphone = $value2;
                                }
                                if ($key2 == 'name'){
                                    $custname = $value2;
                                }
                                if ($key2 == 'id'){
                                    $customerid = $value2;
                                }
                            }
                        }
                    }
                    // connect to database
                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                    $host = 'localhost';
                    $user = 'root';
                    $db = 'flutterwave_assessments';
                    $password = '';

                    $conn = new mysqli($host,$user,$password);
                    mysqli_select_db($conn,$db);

                    $conn = new mysqli($host,$user,$password);
                    mysqli_select_db($conn,$db);

                    // log transaction
                    try{
                        $dt = new DateTime($createddate);
                        $date = $dt->format('Y-m-d');

                        $query = "INSERT INTO transaction_table (txid, txref, flwref, amount, currency, 
                        chargecode, chargemessage, authmodel, ip, `status`, createddate, chargetype, 
                        customerid, custphone, custnetworkprovider, custname, custemail, merchantid, 
                        merchantname, acctcountry) 
                        VALUES ('$txid', '$txref', '$flwref', '$amount', '$currency', '$chargecode', 
                        '$chargemessage', '$authmodel', '$ip', '$status', '$date', '$chargetype', 
                        '$customerid', '$custphone', '$custnetworkprovider', '$custname', '$custemail', 
                        '$merchantid', '$merchantname', '$acctcountry')";
                        $res = mysqli_query($conn,$query);
                    }
                    catch(mysqli_sql_exception $ex){
                        throw $ex;
                        echo $ex->getMessage();
                    }
                }                
            }
        }
    }
    catch(Exception $e){
        echo $e->getMessage();
    }

?>