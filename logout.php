<?php
ini_set('session.cookie_domain', '.iungo.io' ); 
session_start();
include 'connect.php';
// $query = "UPDATE `accounts` SET `status`=NULL,`category`=NULL, `spaceName`=NULL, `roomId`=NULL  WHERE `username` = '".$_SESSION['name']."'";
$result = mysqli_query($con,"UPDATE accounts SET `status`=NULL,`category`=NULL,`roomId`=NULL WHERE id = '".$_SESSION['id']."'");
$row=mysqli_fetch_row($result);
session_destroy();
// Redirect to the login page:
header('Location: ../index.php');
?>