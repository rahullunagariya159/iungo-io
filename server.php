<?php
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
// connect to the database
include 'connect.php';

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



  // first check the database to make sure 
  // a user does not already exist with the same  email
  $user_check_query = "SELECT * FROM accounts WHERE email='$email' AND spaceId='$getSpceId' LIMIT 1";
  $result = mysqli_query($con, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
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
    }

    $spaceName = $getSpaceName;
  }

  // if($getHostName[0] == "kellogg" && !isset($_POST['access']))
  // {
  //   // Validate email
    
  //   if(substr($email,strpos($email,"@")) != "@kellogg.northwestern.edu") {
  //     //echo "innnn email valie";
  //     array_push($errors, "Please use your organizations email or request moderator to join the space , fill the form below");
  //   }

  //   $spaceName = "kellogg";
  // }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
      $password = password_hash($password_1, PASSWORD_DEFAULT);
//  	$password = md5($password_1);//encrypt the password before saving in the database

          

            $query = "INSERT INTO accounts (firstname,name, email, password, spaceName,spaceId) 
                VALUES('$firstname','$name', '$email', '$password', '$spaceName','$getSpceId')";
          
        	
        	mysqli_query($con, $query);


      	
      	$last_id = mysqli_insert_id($con);



          if(isset($_POST['access']))
          {

            // $query = "INSERT INTO accounts (firstname,name, mobile, email, password, spaceName, aboutyou,spaceId,status) 
            //     VALUES('$firstname','$name', '$mobile', '$email', '$password', '$spaceName', '$aboutyou','$getSpceId',2)";
              $reason = mysqli_real_escape_string($con, $_POST['reason']);
              $qryValidate = "INSERT INTO validate (spacename,user_id, reason, access, email,spaceId) 
                VALUES('$getSpaceName','$last_id', '$reason', 0, '$email','$getSpceId')";

              mysqli_query($con, $qryValidate);
          
          }

        // $current_date_time = time();
      	$current_date_time =  date("Y-m-d h:s a");


      	$venuemembers_query = "INSERT INTO venuemembers (UserID, VenueID, DateJoined, LastConnection, TotalConnections, ConnectionTime, Role,spaceId) VALUES ('$last_id','$getGeneralRoomId','$current_date_time','',0, '', 'member','$getSpceId')";
      	
      	$result = mysqli_query($con,$venuemembers_query);

        	$_SESSION['firstname'] = $firstname;
        	$_SESSION['success'] = "You are now logged in";
          $_SESSION['spaceId'] = $getSpceId;
          $_SESSION['spaceUrl'] =  $getSpaceUrl;

          if(!isset($_POST['access'])){
        	  header('location: index.php');
          }
          else{
            include('validate.php');
          }
  }
}

