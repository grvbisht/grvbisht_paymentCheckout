<?php $singleUse="0";$amount;$fname='0';$lname;$country;$state;$city;$zipcode;$gmail='0';$address;$number;$sql;
if(isset($_POST['firstname'])){
$amount=$_POST['amount'];
$fname=$_POST['firstname'];

$lname=$_POST['lastname'];

$gmail=$_POST['gmail'];

$country=strtoupper($_POST['country']);

$state=strtoupper($_POST['state']);


$city=$_POST['city'];

$number=$_POST['number'];

$zipcode=$_POST['zipcode'];

$address=$_POST['address'];
}
?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://hosted.paysafe.com/checkout/v2/paysafe.checkout.min.js"></script>

</head>
<body>
<script>
  if('<?php echo $fname;?>'!=='0'){
    function checkout(o){
        var obj={
            "currency": "USD",
            "amount": parseInt("<?php echo $amount?>"),
            "locale": "en_US",
            "customer": {
                "firstName":"<?php echo $fname?>",
                "lastName": "<?php echo $lname?>",
                "email": "<?php echo $gmail?>",
                "phone": "<?php echo $number?>",
                "dateOfBirth": {
                    "day": 1,
                    "month": 7,
                    "year": 1990
                }
            },
            "billingAddress": {

                "street": "<?php echo $address?>",
                "street2": "xyz",
                "city": "<?php echo $city?>",
                "zip": "<?php echo $zipcode?>",
                "country": "<?php echo $country?>",
                "state": "<?php echo $state?>"
            },
            "showSaveCardCheckboxes":true,
            "environment": "TEST",
            "merchantRefNum": "15599005976151",
            "canEditAmount": false,
            "merchantDescriptor": {
                "dynamicDescriptor": "XYZ",
                "phone": "1234567890"
            },
            "displayPaymentMethods":["card"],
            "paymentMethodDetails": {
                "paysafecard": {
                    "consumerId": "1232323"
                }

            }
        };

            if(o!=0){
            obj.singleUseCustomerToken=o;
            console.log(obj.singleUseCustomerToken);}
        paysafe.checkout.setup("cHVibGljLTc3NTE6Qi1xYTItMC01ZjAzMWNiZS0wLTMwMmQwMjE1MDA4OTBlZjI2MjI5NjU2M2FjY2QxY2I0YWFiNzkwMzIzZDJmZDU3MGQzMDIxNDUxMGJjZGFjZGFhNGYwM2Y1OTQ3N2VlZjEzZjJhZjVhZDEzZTMwNDQ=",obj ,function(instance, error, result) {
                if (result && result.paymentHandleToken) {

                    if(result.customerOperation =="ADD")
                    { console.log("enterred");
                        $.ajax({
                            type:'POST',
                            url:'server.php',
                            data:'token='+result.paymentHandleToken+'&'+'value='+true+'&'+'amount='+"<?php echo $amount?>"+'&'+'gmail='+"<?php echo $gmail?>",
                            success:function(data) {
                                console.log(data);
                                if(result.token !==null){
                                    instance.showSuccessScreen();
                                }
                                else{
                                    instance.showFailureScreen();
                                }
                            }
                            ,
                            error: function(request, status, error) {
                                instance.showFailureScreen();}
                        });
                    }
                    else{
                        $.ajax({
                            type:'POST',
                            url:'server.php',
                            data:'token='+result.paymentHandleToken+'&'+'value='+false+'&'+'amount='+"<?php echo $amount?>"+'&'+'gmail='+"<?php echo $gmail?>",
                            success:function(data) {
                                console.log(data);
                                if(result.token !==null){

                                    instance.showSuccessScreen();

                                }
                                else{
                                    instance.showFailureScreen();
                                }
                            }
                            ,
                            error: function(request, status, error) {    instance.showFailureScreen();}
                        });

                    }
                }
                else{console.log(error);}
            }
            , function(stage, expired) {
                switch(stage) {
                    case "PAYMENT_HANDLE_NOT_CREATED": console.log("Payment handle not created");break;
                    case "PAYMENT_HANDLE_CREATED": console.log("Payment handle created");break;
                    case "PAYMENT_HANDLE_REDIRECT": console.log("Payment handle redirect");break;
                    case "PAYMENT_HANDLE_PAYABLE": console.log("Payment handle payable");break;
                    default: console.log("default");
                }

            });
    }
}
</script>
<?php

include 'db_connection.php';
    $con=OpenCon();
        $sql = "select * from customers where merchantCustomerId="."'".$gmail."'";
      $result=$con->query($sql);
      CloseCon($con);
            if($result->num_rows===0)
          {
             if($fname!=='0') {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.test.paysafe.com/paymenthub/v1/customers");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);

                curl_setopt($ch, CURLOPT_POST, TRUE);
                $id1 = $gmail;
                $data = array(
                    "merchantCustomerId" => $id1,
                    "locale" => "en_US",
                    "firstName" => $fname,
                    "middleName" => "",
                    "lastName" => $lname,
                    "email" => $gmail,
                    "phone" => $number,

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
                 $output = json_decode($response);
                if (isset($output->id)) {
                    $id2 = $output->id;
                }
                $sql = "insert into customers(merchantCustomerId,customerId) values(" ."'". $id1."'" . "," ."'". $id2."'" . ")";
                $result = $con->query($sql);
                CloseCon($con);
             ?>
                    <button onclick="checkout('0')"> <h1>Click here to pay</h1> </button>

<?php
                }

                        }

        else
        {
            $ch = curl_init();
            $con=OpenCon();
            if (mysqli_connect_errno()) {
                console.log("connection failed");
            }
            else {
                $sql="select customerId from customers where merchantCustomerId="."'".$gmail."'";
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
                    
                    curl_close($ch);
                    if(isset($response)) {
                    $obj = json_decode($response);
                    if(isset($obj->singleUseCustomerToken)) 
                        $singleUse = $obj->singleUseCustomerToken;
                                    }
                   ?>
                   
                    <button onclick="checkout('<?php echo $singleUse ;?>') "> <h1>Click here to pay</h1> </button>
<?php

                }
            }
        }

?>
</body>
</html>
