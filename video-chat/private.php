<?php 
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
include '../connect.php';


if(isset($_GET['r'])){

$category = $_GET['r'];
$spaceName=NULL;
if(explode('.',  $_SERVER['HTTP_HOST'])[0]!="iungo"){
       $spaceName = "kellogg";
    }
// echo  $_POST['createroom'];
$query = "SELECT * FROM accounts WHERE `id` = '".$_SESSION['id']."' LIMIT 1";
$result = mysqli_query($con,$query);
$row = mysqli_fetch_array($result);
$id = $row[0];
$user_id = $row['id'];


$query = "SELECT * FROM category WHERE Name ='$category' AND type = 'Private' LIMIT 1";
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

$query_check = "SELECT members FROM category WHERE Name = '$category'";
$result_check = mysqli_query($con,$query_check);
$resultmem_check = mysqli_fetch_row($result_check)[0];
$members_check = explode(",",$resultmem_check);
if (in_array($id,$members_check, TRUE)) {
	# code...
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
	
	<!-- Hotjar Tracking Code for www.iungo.io -->
	<script>
		(function(h,o,t,j,a,r){
			h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
			h._hjSettings={hjid:1792322,hjsv:6};
			a=o.getElementsByTagName('head')[0];
			r=o.createElement('script');r.async=1;
			r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
			a.appendChild(r);
		})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
	</script>

	</head>
	<body>
		<form name=‘fr’ action=chatroom.php method=POST id="fr">
        <input type="text" name="createroom" value="<?php echo $category ?>" hidden>
        <input type="text" name="room_domain" value="<?php echo $spaceName ?>" hidden>


	<!-- <include type=‘hidden’ name=‘var1’ value=‘val1’> -->
	<!-- <include type=‘hidden’ name=‘var2’ value=‘val2’> -->
	</form>
	<script type="text/javascript" >
		document.getElementById("fr").submit();
	</script>
	</body>
	</html>
	<?php
}

?>