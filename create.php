<?php
session_start();
include 'db_connection.php';
if(isset($_SESSION['fname'])) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.test.paysafe.com/paymenthub/v1/customers");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_POST, TRUE);
    $id1 = $_SESSION['gmail'];
    $data = array(
        "merchantCustomerId" => $id1,
        "locale" => "en_US",
        "firstName" => $_SESSION['fname'],
        "middleName" => "",
        "lastName" => $_SESSION['lname'],
        "email" => $_SESSION['gmail'],
        "phone" => $_SESSION['phonenumber'],

    );
    $dat = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dat);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: " . "Basic cHJpdmF0ZS03NzUxOkItcWEyLTAtNWYwMzFjZGQtMC0zMDJkMDIxNDQ5NmJlODQ3MzJhMDFmNjkwMjY4ZDNiOGViNzJlNWI4Y2NmOTRlMjIwMjE1MDA4NTkxMzExN2YyZTFhODUzMTUwNWVlOGNjZmM4ZTk4ZGYzY2YxNzQ4",
        "Simulator: EXTERNAL"
    ));

    $response = curl_exec($ch);

    curl_close($ch);

    $con = OpenCon();
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    $id2 = 0;
 
    if (isset($response)) {
        $output = json_decode($response);
        $id2 = $output->id;
    }
    $sql = "insert into customers(merchantCustomerId,customerId) values(" ."'". $id1."'" . "," ."'". $id2."'" . ")";
    $result = $con->query($sql);
    CloseCon($con);
    if(!empty($result)){

    header('Location:client.php');}
    else {
        session_destroy();

    }

}

