<?php
// Database configuration
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
include 'connect.php';
$schoolname = $_POST['schoolname'];

$s2name = $_POST['s2name'];
$s3name = $_POST['s3name'];
$mname = $_POST['mname'];

$s2mobile = $_POST['s2mobile'];
$s3mobile = $_POST['s3mobile'];
$mmobile = $_POST['mmobile'];

$s2email = $_POST['s2email'];
$s3email = $_POST['s3email'];
$memail = $_POST['memail'];

echo $memail;

$sql = "UPDATE accounts SET `schoolname`='".$schoolname."', s2name='".$s2name."',`s2mobile`='".$s2mobile."',`s2email`='".$s2email."',s3name='".$s3name."',`s3mobile`='".$s3mobile."',`s3email`='".$s3email."',`mname`='".$mname."',`mmobile`='".$mmobile."',`memail`='".$memail."' WHERE id = '".$_SESSION['id']."'" ;
//$sql = "UPDATE MyGuests SET lastname='Doe' WHERE id=2";

if ($con->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $con->error;
}

$con->close();
 if(empty($_GET['status'])){
     header('Location:home.php?status=1');
     exit;
}
?>