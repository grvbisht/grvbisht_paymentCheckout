<?php session_start() ?>
<?php
include 'db_connection.php';
if(isset($_POST['token']) && isset($_POST['value'])){
                
    $value=$_POST['token'];
    $value2=$_POST['value'];
    if($value2==true) {
        $ch = curl_init();
        $con = OpenCon();
        if (mysqli_connect_errno()) {
            console . log("connection failed");
        } else {
            $sql = "select customerId from customers where merchantCustomerId=".$_SESSION['gmail'];

            $result = $con->query($sql);
            CloseCon($con);
            if ($result) {
                $row = $result->fetch_row();
                $url = "https://api.test.paysafe.com/paymenthub/v1/customers/" . $row[0] . "/paymenthandles";
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);

                curl_setopt($ch, CURLOPT_POST, TRUE);
                $data = array("paymentHandleTokenFrom" => $value);
                $dat = json_encode($data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dat);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json",
                    "Authorization: " . "Basic cHJpdmF0ZS03NzUxOkItcWEyLTAtNWYwMzFjZGQtMC0zMDJkMDIxNDQ5NmJlODQ3MzJhMDFmNjkwMjY4ZDNiOGViNzJlNWI4Y2NmOTRlMjIwMjE1MDA4NTkxMzExN2YyZTFhODUzMTUwNWVlOGNjZmM4ZTk4ZGYzY2YxNzQ4",
                    "Simulator: EXTERNAL"
                ));

                $response = curl_exec($ch);
               

            }

        }
    }
    curl_close($ch);    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.test.paysafe.com/paymenthub/v1/payments");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
     
    curl_setopt($ch, CURLOPT_POST, TRUE);
  
$random_value=rand(1,100000000000); 
  
$data=array(
           "merchantRefNum"=> $random_value,
            "amount"=>$_SESSION['amount'],
            "currencyCode"=>"USD",
       "dupCheck"=> true,
  "settleWithAuth"=> true,
  "paymentHandleToken"=> $value,
  "description"=>"Magazine subscription"
   );
$dat=json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$dat);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: "."Basic cHJpdmF0ZS03NzUxOkItcWEyLTAtNWYwMzFjZGQtMC0zMDJkMDIxNDQ5NmJlODQ3MzJhMDFmNjkwMjY4ZDNiOGViNzJlNWI4Y2NmOTRlMjIwMjE1MDA4NTkxMzExN2YyZTFhODUzMTUwNWVlOGNjZmM4ZTk4ZGYzY2YxNzQ4",
        "Simulator: EXTERNAL"
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    session_destroy();
    echo $response;
}