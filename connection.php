<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $host = 'localhost';
    $user = 'root';
    $db = 'flutterwave_assessments';
    $password = '';

    $conn = new mysqli($host,$user,$password);
    mysqli_select_db($conn,$db);

?>