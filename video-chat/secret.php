<?php 
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
include '../connect.php';


if(isset($_POST['join'])){

$category = $_POST['createroom'];
$spaceName=NULL;
if($_POST['room_domain']){
    $spaceName = "kellogg";
}
// echo  $_POST['createroom'];
$query = "SELECT * FROM accounts WHERE `id` = '".$_SESSION['id']."' LIMIT 1";
$result = mysqli_query($con,$query);
$row = mysqli_fetch_array($result);
$id = $row[0];
$user_id = $row['id'];


$query = "SELECT * FROM category WHERE Name ='$category' LIMIT 1";
$result = mysqli_query($con,$query);
$row = mysqli_fetch_array($result);
$members = $row['members'];
$members = explode(",", $members);
$type = $row['type'];
$access = $row['access'];
$access = explode(",", $access);
$createdby = $row['createdBy'];

if($createdby != $user_id)
	{
		// echo "Creator and user not same <br>";

		//Check if already requested 
		if (in_array($id,$access, TRUE))
		{
			echo "Already requested... Check Back in 24 hrs";
		}
		else
		{
			$access = implode(",", $access);
			$access = $access."".$id.",";
			$query_access = "UPDATE category SET access = '$access' WHERE Name = '".$category."' ";
			mysqli_query($con,$query_access);
			echo "<h1>This is not a public chatroom, a request has been sent to the moderators,Please check back in a few hours</h1>";
		}
		
	}


	else
	{
		echo "Please Go back to main page and join again";
		$count = COUNT($members)-1;
		if (in_array($id,$members, TRUE))
		{
		}
		else{
			$membersstr = implode(",",$members);
			$membersup = $membersstr."".$id.",";
			$querymemupdate = "UPDATE category SET members = '".$membersup."' WHERE Name = '".$category."'";
			mysqli_query($con,$querymemupdate);
			$count = $count + 1;
		}
		$querymemupdate = "UPDATE category SET totalusers = '".$count."' WHERE Name = '".$category."'";
		mysqli_query($con,$querymemupdate);
	}
}


?>