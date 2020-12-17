<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $host = 'xxxxxxxxxx';
    $user = 'xxxxxx';
    $db = 'flutterwave_assessments';
    $password = 'xxxxxxxxxxxxxx';

    $conn = new mysqli($host,$user,$password);
    mysqli_select_db($conn,$db);

?>
