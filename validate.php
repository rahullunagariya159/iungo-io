<?php 
// ini_set('session.cookie_domain', '.iungo.io' );
// session_start();
// // Change this to your connection info.
// include 'connect.php';
function validateUsers(){

	if(isset($_POST['access'])){
		$user_id = $_POST['user_id'];
		$reason = $_POST['reason'];
		$spacename = explode('.',  $_SERVER['HTTP_HOST'])[0];
	// echo $_GET['user_id'];
		$user_check_query = "SELECT user_id FROM validate WHERE user_id='$user_id' LIMIT 1";
	    $result = mysqli_query($con, $user_check_query);
	    $user = mysqli_fetch_assoc($result);
	    if ($user) { // if user exists
	    	array_push($errors, "Request already sent");
	    	return "403";
		}
		else
		{  
			$user_check_query = "SELECT * FROM accounts WHERE id='$user_id' LIMIT 1";
		    $result = mysqli_query($con, $user_check_query);
		    $user = mysqli_fetch_row($result);
		    if($user){
		    	$validate_query= "INSERT INTO `validate`(`spacename`, `user_id`,`reason`,`email`) VALUES ('$spacename','$user_id','$reason','$user[5]')";
				mysqli_query($con,$validate_query);
				array_push($errors, "Request sent to admin");
				// return "202";
		    }	
		    else{
		    	//array_push($errors, "Please Register First");
		    	$success =  "Your request successfully send";
		    	return "202";
		    	// sleep(20);

		    	// header('location: index.php');

		    }
			
		}
	}
	else
	{
		// array_push($errors, "Please Type user_id");
	}

}
?>