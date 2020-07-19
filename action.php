<?php
session_start();
if(isset($_POST['firstname'])) {
    $_SESSION['fname'] = $_POST['firstname'];
    $_SESSION['lname'] = $_POST['lastname'];
    $_SESSION['gmail'] = $_POST['gmail'];
    $_SESSION['phonenumber'] = $_POST['number'];
    $_SESSION['country'] = $_POST['country'];
    $_SESSION['state'] = $_POST['state'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['amount'] = $_POST['amount'];
    $_SESSION['zipcode']=$_POST['zipcode'];
    $_SESSION['city']=$_POST['city'];
    header('Location:index2.php');
}