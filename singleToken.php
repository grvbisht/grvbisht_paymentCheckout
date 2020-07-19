<?php
session_start();
?>
<?php
include "db_connection.php";
$ch = curl_init();
$con=OpenCon();
if (mysqli_connect_errno()) {
    console.log("connection failed");
}
else {
$sql="select customerId from customers where merchantCustomerId="."'".$_SESSION['gmail']."'";
$result=$con->query($sql);

CloseCon($con);
if($result)
{
  $row=$result->fetch_row();
  $url="https://api.test.paysafe.com/paymenthub/v1/customers/".$row[0]."/singleusecustomertokens";
  curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);
$data=array(
    "merchantRefNum"=>"",
    "paymentTypes" =>array("CARD")
);
$d=json_encode($data);
curl_setopt($ch, CURLOPT_POSTFIELDS,$d);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: "."Basic cHJpdmF0ZS03NzUxOkItcWEyLTAtNWYwMzFjZGQtMC0zMDJkMDIxNDQ5NmJlODQ3MzJhMDFmNjkwMjY4ZDNiOGViNzJlNWI4Y2NmOTRlMjIwMjE1MDA4NTkxMzExN2YyZTFhODUzMTUwNWVlOGNjZmM4ZTk4ZGYzY2YxNzQ4",
    "Simulator: EXTERNAL"
));

$response = curl_exec($ch);
$obj=json_decode($response);
curl_close($ch);
$singleUse=$obj->singleUseCustomerToken;
$_SESSION['token']=$singleUse;
   header('Location:client.php');
 }
}