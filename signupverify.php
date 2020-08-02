<?php 
	
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
// connect to the database
include 'connect.php';
include_once('phpsendmail/emailSendApi.php');

// REGISTER USER
if (isset($_POST['reg_user']) || isset($_POST['access'])) {

	  // initializing variables
	  $firstname = "";
	  $name = "";
	  $email = "";
	  $mobile  = "";
	  $spaceName = NULL;
	  $errors = array();
	  $success = "";

	  // receive all input values from the form
	  $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
	  $name = mysqli_real_escape_string($con, $_POST['name']);
	  // $aboutyou = mysqli_real_escape_string($con, $_POST['aboutyou']);
	  // $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
	  $email = mysqli_real_escape_string($con, $_POST['email']);
	  $password_1 = mysqli_real_escape_string($con, $_POST['password_1']);
	  $password_2 = mysqli_real_escape_string($con, $_POST['password_2']);



	  

	  // form validation: ensure that the form is correctly filled ...
	  // by adding (array_push()) corresponding error unto $errors array
	  if (empty($firstname)) { array_push($errors, "firstname is required"); }
	  // if (empty($aboutyou)) { array_push($errors, "Description is required"); }
	  // if (empty($mobile)) { array_push($errors, "mobile is required"); }
	  if (empty($email)) { array_push($errors, "Email is required"); }
	  if (empty($password_1)) { array_push($errors, "Password is required"); }
	  if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	  }


	  // subdomain kellogg
	  $getHostName = explode('.',  $_SERVER['HTTP_HOST']);  
	  // print_r($_SERVER['HTTP_HOST']);
	  // die;
	  $spaceName;
	  $getSpaceQry = "select * from spaces where name='$getHostName[0]'";
	  $getqryExe = mysqli_query($con,$getSpaceQry); 
	   $getSpaceRes = mysqli_fetch_assoc($getqryExe);

	   $getSpceId = $getSpaceRes['id'];
	   $getEmailExtension = $getSpaceRes['emailExtension'];
	   $getSpaceName = $getSpaceRes['name'];
	   $getSpaceUrl = $getSpaceRes['spaceUrl'];
   // echo $getSpceId; 
   // echo "<pre>";
   // print_r($getSpaceRes);

	   $setSessionStatus = false;

  // first check the database to make sure 
  // a user does not already exist with the same  email
	  $user_check_query = "SELECT * FROM accounts WHERE email='$email' AND spaceId='$getSpceId' LIMIT 1";
	  $result = mysqli_query($con, $user_check_query);
	  $user = mysqli_fetch_assoc($result);
	  
	  if ($user) { // if user exists
	    if ($user['email'] === $email) {
	      array_push($errors, "email already exists");
	      echo json_encode(["error"=>"email already exists","errorCode"=>"403"]);die;
	    }
	   
	  }
	  else
	  {
	  	$setSessionStatus = true;
	  }

 
	$getGeneralCategory = "SELECT id from category where Name='General' and  spaceId='".$getSpceId."'";
	$getGeneralCateExec = mysqli_query($con, $getGeneralCategory);
    $getGeneralRes = mysqli_fetch_assoc($getGeneralCateExec);

    $getGeneralRoomId = $getGeneralRes['id'];
   
   

  // echo $getEmailExtension;
  // echo $getSpaceName;
  //echo $getHostName[0];

	  if(strtolower($getHostName[0]) == strtolower($getSpaceName) && !isset($_POST['access']))
	  {
	    // Validate email

	    if(substr($email,strpos($email,"@")) != $getEmailExtension) {
	     
	      array_push($errors, "Please use your organizations email or request moderator to join the space , fill the form below");
	      echo json_encode(["error"=>"Please use your organizations email or request moderator to join the space , fill the form below","errorCode"=>"405"]);die;
	    }

	    $spaceName = $getSpaceName;
	  }


	  if($setSessionStatus)
	  {




	  		$fourdigitrandom = rand(1000,9999); 

	  		// $_SESSION['signConfFname'] = @$_POST['firstname'];
	  		// $_SESSION['signConfName'] = @$_POST['name'];
	  		session_regenerate_id();
	  		 $_SESSION['signConfEmail'] = @$_POST['email'];
	  		 $_SESSION['verifyEmailToken'] = $fourdigitrandom;

	  		 // "test1002.dds@gmail.com"
	  		 $sendMailStatus = iungo($fourdigitrandom,$email);
	  		
	  		 if($sendMailStatus)
	  		 {
	  		 	echo json_encode(["success"=>"done","successCode"=>"202","data"=>$_POST]);die;
	  		 }
	  		 else
	  		 {
	  		 	echo json_encode(["error"=>"mail not send","errorCode"=>"405"]);die;

	  		 }

	  		// $_SESSION['signConfPass'] = @$_POST['password_1'];
	  		// $_SESSION['signConfPassConfi'] = @$_POST['password_2'];
	  		// $_SESSION['signConfReason'] = @$_POST['reason'];


	  		// $_SESSION['confRegUser'] = @$_POST['reg_user'];
	  		// $_SESSION['confRegAccess'] = @$_POST['access'];



	  	
	  }
 }
?>