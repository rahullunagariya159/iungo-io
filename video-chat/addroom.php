<?php
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
include '../connect.php';
$createdBy = $_SESSION['id'];
$user_id = $_SESSION['id'];
$roomname = $_POST['roomname'];
$roomdesc = $_POST['roomdesc'];
$type = $_POST['type'];
$queryadd = "INSERT INTO category(Name, description, createdBy, type) VALUES ('$roomname','$roomdesc','$createdBy','$type')";
mysqli_query($con, $queryadd);

$last_id = mysqli_insert_id($con);

$current_date_time = strtotime('now');

$venuemembers_query = "INSERT INTO venuemembers (UserID, VenueID, DateJoined, LastConnection, TotalConnections, ConnectionTime, Role) VALUES ('$user_id','$last_id','$current_date_time','',0, '', 'owner')";
		 
$result = mysqli_query($con,$venuemembers_query);
			
header('location: index.php');

?>