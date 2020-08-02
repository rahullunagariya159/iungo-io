<?php 
		

		ini_set('session.cookie_domain', '.iungo.io' );
		session_start();
		include '../connect.php';
		@$spaceId = @$_SESSION['spaceId'];

		if(!isset($_SESSION['id']))
		{
		  header("location:../index.php");
		}

		if(isset($_POST['createroom_id']))
		{
			$roomName = $_POST['createroom'];
			$roomId = $_POST['createroom_id'];
			$userId = $_SESSION['id']; 	
			$current_date_time = date("Y-m-d h:s a");

			 $allReadyPending_Qry = "SELECT * FROM venuemembers WHERE `UserID`=".$userId." AND `VenueID` = '".$roomId."' AND `spaceId`='".$spaceId."' "; 

               $checkPendingExec = mysqli_query($con, $allReadyPending_Qry);
              $checkPending_row = mysqli_fetch_row($checkPendingExec);
              
              if($checkPending_row > 0)
              {
              	$setPendingQry = "UPDATE venuemembers SET Role='pending',status='pending' WHERE VenueID='$roomId' AND UserID='$userId'";
              }
              else
              {
              	$setPendingQry = "INSERT INTO venuemembers (UserID,VenueID,DateJoined,Role,spaceId) VALUES ('$userId','$roomId','$current_date_time','pending','$spaceId')";
              }

			


			if (mysqli_query($con, $setPendingQry)) {
				$dataInsertSuccess = true;
				header('location:index.php');
			}

		
				
		}
		


?>