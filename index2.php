<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

</head>
<body></body>
</html>
<?php
if(isset($_SESSION['gmail'])){
include 'db_connection.php';
$con=OpenCon();
$mail=$_SESSION['gmail'];
$sql="select * from customers where merchantCustomerId="."'".$mail."'";
$result=$con->query($sql);

if ($result === FALSE) {
session_destroy(); 
 echo "Error: " . $sql . "<br>" . $conn->error;
} else {
CloseCon($con);
if($result->num_rows===0)
{
 header('Location:create.php');   }
else
{
 header('Location:singleToken.php');
}

}
}

