 <?php 
 include 'videoChatConstant.php';
 include '../connect.php';
 

	$sitPath = getcwd();
	@$spaceId = $_SESSION['spaceId'];
	 $findOwnerOfSpace=0;
	 // echo $spaceId;
	 	
	//echo  @$_SESSION['id'];

	if(isset($spaceId))
	{
		$userId =  @$_SESSION['id']; 
	   $getSpaceQry = "select * from spaces where id='$spaceId' and ownerId='$userId'";
	   $getqryExe = mysqli_query($con,$getSpaceQry); 
	   $getSpaceRes = mysqli_fetch_assoc($getqryExe);
	   $findOwnerOfSpace = mysqli_num_rows($getqryExe); 

	   // if($findOwnerOfSpace > 0)
	   // {
	   // 		 $getS = "select * from spaces where id='$spaceId' and ownerId='$userId'";
		  //  $getqryExe = mysqli_query($con,$getSpaceQry); 
		  //  $getSpaceRes = mysqli_fetch_assoc($getqryExe);
		  //  $findOwnerOfSpace = mysqli_num_rows($getqryExe); 
	   // }

	   $getSpceId = $getSpaceRes['id'];
	   $getEmailExtension = $getSpaceRes['emailExtension'];
	   $getSpaceName = $getSpaceRes['name'];
	   $getSpaceUrl = $getSpaceRes['spaceUrl'];

	}

	if(isset($_POST['venueIdd'])){
		$chatroomId = @$_POST['venueIdd'];

		$queryRoom = "SELECT * FROM category WHERE id=$chatroomId";

		$allRoomResult = mysqli_query($con, $queryRoom);
 
   		$getAllRoomList = mysqli_fetch_all($allRoomResult, MYSQLI_ASSOC);
   	
   	    echo json_encode($getAllRoomList);die;
		
		
	}
	
	if(isset($_POST['createRoom']))
	{	
		$venueAction = trim(@$_POST['venueAction']," ");
		$venueID = trim(@$_POST['venueId']," "); 
		$venueName = trim(@$_POST['venueName']," ");
		$venueDesc = trim(@$_POST['venueDesc']," ");
		$venueType = trim(@$_POST['venueType']," ");
		$venueTypeCap = ucfirst($venueType);
		$venuePicture = @$_POST['venuePicture'];
		$moderators =  @$_POST['moderators'];
		$userId =  @$_SESSION['id'];  
		@$spaceId = @$_SESSION['spaceId'];
		// echo $venueName;
		// echo $venueDesc;
		// echo $venueType;
		// print_r($venuePicture);
		// print_r($moderators);
		$getSpecRoomList;
		$oldModratorList;
		if($venueAction == "update")
		{
			

			$queryRoom = "SELECT * FROM category WHERE id=$venueID AND spaceId=$spaceId";

			$allRoomResult = mysqli_query($con, $queryRoom);
	 
	   		$getSpecRoomList = mysqli_fetch_all($allRoomResult, MYSQLI_ASSOC);
	   		
	   		$getSpecRoomList[0]['moderator'];
	   		
	   		$oldModratorList = explode(",", $getSpecRoomList[0]['moderator']);

		}
		
		$moderatorsIns = "";
		$dataInsertSuccess = false;
		$path = getcwd();

		$traget_dir_venue = $path."/venueImages/";
		
		$target_file = $traget_dir_venue . basename($_FILES["venuePicture"]["name"]);
		$orignalFileNameVenue = basename($_FILES["venuePicture"]["name"]);


			if($orignalFileNameVenue == "")
			{
				

				if(empty($moderators) || $venueTypeCap != "Private")
				{
					$moderatorsIns;
					if($venueAction == "update")
					{	
						
						$emptyMem = "";
						$moderatorsIns = "UPDATE category SET Name='$venueName',description='$venueDesc',type='$venueTypeCap',moderator='$emptyMem' WHERE id='$venueID' ";

						if($getSpecRoomList[0]['moderator'] != "")
				   		{
				   			// $idListString = implode(",",$getSpecRoomList[0]['members']);
				   			//$idListString = $getSpecRoomList[0]['members'];
				   			$remMem = "DELETE FROM venuemembers WHERE VenueID='$venueID' and Role='member'";
				   			
							mysqli_query($con,$remMem);
				   			
				   		}
						

					}
					else
					{
						$moderatorsIns = "INSERT INTO category (Name, description,createdBy,type,spaceId) VALUES ('$venueName','$venueDesc','$userId','$venueTypeCap','$spaceId')";	
					}

					mysqli_query($con, $moderatorsIns);

					$last_id = mysqli_insert_id($con);

					$current_date_time = date("Y-m-d h:s a");


					if($venueAction != "update")
					{
						$moderatorsVenueMem = "INSERT INTO venuemembers (UserID,VenueID,DateJoined,Role,spaceId) VALUES ('$userId','$last_id','$current_date_time','member','$spaceId')";


						if (mysqli_query($con, $moderatorsVenueMem)) {
							$dataInsertSuccess = true;
						}
					}


					

				}
				else
				{	
					
					$totalModretors = count($moderators);

					$modratorList = implode(",",$moderators);
					
					$moderatorsIns;
					
					
					
						

					if($venueAction == "update")
					{

						$moderatorsIns = "UPDATE category SET Name='$venueName',description='$venueDesc',totalusers='$totalModretors',type='$venueTypeCap',moderator='$modratorList' WHERE id='$venueID'";
					}
					else
					{
						$moderatorsIns = "INSERT INTO category (Name, description, totalusers,createdBy,moderator,type,spaceId) VALUES ('$venueName','$venueDesc','$totalModretors','$userId','$modratorList','$venueTypeCap','$spaceId')";
					}

					mysqli_query($con, $moderatorsIns);

					$last_id = mysqli_insert_id($con);

					$current_date_time = date("Y-m-d h:s a");

					if($venueAction != "update")
					{
						$moderatorsVenueMem = "INSERT INTO venuemembers (UserID,VenueID,DateJoined,Role,spaceId) VALUES ('$userId','$last_id','$current_date_time','member','$spaceId')";

						mysqli_query($con, $moderatorsVenueMem);

					}

					if($venueAction == "update"){

						// print_r($oldModratorList);

						// print_r($moderators);
						//echo " remove";
						$removeOldMemValue = array_diff($oldModratorList,$moderators); //remove
						foreach ($removeOldMemValue as $key => $value) {
						
							$remMemOld = "DELETE FROM venuemembers WHERE VenueID='$venueID' and Role='member' and UserID='$value'";
							

							if (mysqli_query($con,$remMemOld)) {
								$dataInsertSuccess = true;
							}
							else
							{
								$dataInsertSuccess = false;
							}
							
						}
						
						//echo " insert";
						$insertNewMemValue = array_diff($moderators,$oldModratorList);  //insert
						
						foreach ($insertNewMemValue as $key => $value) {

							$newVenueModrator = "INSERT INTO venuemembers (UserID,VenueID,DateJoined,Role,spaceId) VALUES ('$value','$venueID','$current_date_time','member','$spaceId')";

								
							if (mysqli_query($con, $newVenueModrator)) {
								$dataInsertSuccess = true;
							}
							else
							{
								$dataInsertSuccess = false;
							}
							# code...
						}
							
					}
					else
					{


						foreach ($moderators as $key => $value) {

							
						$moderatorsVenueModrator = "INSERT INTO venuemembers (UserID,VenueID,DateJoined,Role,spaceId) VALUES ('$value','$last_id','$current_date_time','member','$spaceId')";

						

							if (mysqli_query($con, $moderatorsVenueModrator)) {
								$dataInsertSuccess = true;
							}
							else
							{
								$dataInsertSuccess = false;
							}

						}
					}

				}

					

			}
			else
			{
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				$fileExtensionChk = "";
				$fileIsUpload = false;
				
					
					$check = getimagesize($_FILES["venuePicture"]["tmp_name"]);



					 if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
						&& $imageFileType != "gif" ) {
						   $fileExtensionChk =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
							$uploadOk = 0;
						} 
						else{
							$fileExtensionChk ="";
					    	$uploadOk = 1;
						}	

						$temp = explode(".", $_FILES["venuePicture"]["name"]);
						$newfilename = round(microtime(true)) . '.' . end($temp);

						// Check if $uploadOk is set to 0 by an error
						if ($uploadOk == 0) {
						 $fileIsUpload = false;
						// if everything is ok, try to upload file
						} else {
						  if (move_uploaded_file($_FILES["venuePicture"]["tmp_name"], $traget_dir_venue.$newfilename)) {
						    //echo "The file ". basename( $_FILES["profile_picture"]["name"]). " has been uploaded.";
						     $fileIsUpload = true;

								if(empty($moderators) || $venueTypeCap != "Private")
								{
									
									$moderatorsIns;
									if($venueAction == "update")
									{
										if($getSpecRoomList[0]['venueImage'] != "")
										{	

											$venueFilePathInfo = $sitPath."/venueImages/".$getSpecRoomList[0]['venueImage'];

											if (file_exists($venueFilePathInfo)) {
													unlink($venueFilePathInfo);
											}
										}
										$emptyMem = "";
										$moderatorsIns = "UPDATE category SET Name='$venueName',description='$venueDesc',type='$venueTypeCap',venueImage='$newfilename',moderator='$emptyMem' WHERE id='$venueID'";

										if($getSpecRoomList[0]['moderator'] != "")
								   		{
								   			// $idListString = implode(",",$getSpecRoomList[0]['members']);
								   			//$idListString = $getSpecRoomList[0]['members'];
								   			$remMem = "DELETE FROM venuemembers WHERE VenueID='$venueID' and Role='member'";
								   			
											mysqli_query($con,$remMem);
								   			
								   		}
										
									}
									else
									{


									$moderatorsIns = "INSERT INTO category (Name, description,createdBy,type,venueImage,spaceId) VALUES ('$venueName','$venueDesc','$userId','$venueTypeCap','$newfilename','$spaceId')";	
									}

									mysqli_query($con, $moderatorsIns);

									$last_id = mysqli_insert_id($con);

									$current_date_time = date("Y-m-d h:s a");


									if($venueAction != "update")
									{
										$moderatorsVenueMem = "INSERT INTO venuemembers (UserID,VenueID,DateJoined,Role,spaceId) VALUES ('$userId','$last_id','$current_date_time','member','$spaceId')";

										

										if (mysqli_query($con, $moderatorsVenueMem)) {
											$dataInsertSuccess = true;
										}

									}
									

								}
								else
								{	
									
									$totalModretors = count($moderators);


									$modratorList = implode(",",$moderators);

									$moderatorsIns;
					
					
					
						

									if($venueAction == "update")
									{

										if($getSpecRoomList[0]['venueImage'] != "")
										{	

											$venueFilePathInfo = $sitPath."/venueImages/".$getSpecRoomList[0]['venueImage'];

											if (file_exists($venueFilePathInfo)) {
													unlink($venueFilePathInfo);
											}
										}


										$moderatorsIns = "UPDATE category SET Name='$venueName',description='$venueDesc',totalusers='$totalModretors',venueImage='$newfilename',type='$venueTypeCap',moderator='$modratorList' WHERE id='$venueID'";
									}
									else
									{


									$moderatorsIns = "INSERT INTO category (Name, description, totalusers,createdBy,moderator,type,venueImage,spaceId) VALUES ('$venueName','$venueDesc','$totalModretors','$userId','$modratorList','$venueTypeCap','$newfilename','$spaceId')";
									}

									mysqli_query($con, $moderatorsIns);

									$last_id = mysqli_insert_id($con);

									$current_date_time = date("Y-m-d h:s a");

									if($venueAction != "update")
									{

										$moderatorsVenueMem = "INSERT INTO venuemembers (UserID,VenueID,DateJoined,Role,spaceId) VALUES ('$userId','$last_id','$current_date_time','member','$spaceId')";

										mysqli_query($con, $moderatorsVenueMem);
									}



									if($venueAction == "update"){

										// print_r($oldModratorList);

										// print_r($moderators);
										//echo " remove";
										$removeOldMemValue = array_diff($oldModratorList,$moderators); //remove
										foreach ($removeOldMemValue as $key => $value) {
										
											$remMemOld = "DELETE FROM venuemembers WHERE VenueID='$venueID' and Role='member' and UserID='$value'";
											

											if (mysqli_query($con,$remMemOld)) {
												$dataInsertSuccess = true;
											}
											else
											{
												$dataInsertSuccess = false;
											}
											
										}
										
										//echo " insert";
										$insertNewMemValue = array_diff($moderators,$oldModratorList);  //insert
										
										foreach ($insertNewMemValue as $key => $value) {

											$newVenueModrator = "INSERT INTO venuemembers (UserID,VenueID,DateJoined,Role,spaceId) VALUES ('$value','$venueID','$current_date_time','member','$spaceId')";

												
											if (mysqli_query($con, $newVenueModrator)) {
												$dataInsertSuccess = true;
											}
											else
											{
												$dataInsertSuccess = false;
											}
											# code...
										}
											
									}
									else
									{

									foreach ($moderators as $key => $value) {

										
									$moderatorsVenueModrator = "INSERT INTO venuemembers (UserID,VenueID,DateJoined,Role,spaceId) VALUES ('$value','$last_id','$current_date_time','member',$spaceId)";

									

									if (mysqli_query($con, $moderatorsVenueModrator)) {
										$dataInsertSuccess = true;
									}else
									{
										$dataInsertSuccess = false;
									}
									
									}
								}

								}





						     // $sql = "UPDATE accounts SET firstname='".$first_name."',name='".$last_name."',mobile='".$mobile."',whoAmI='".$who_i_am."',likeToTalkAbout='".$talk_about."',pronouns='".$pronouns."',profilePicture='".$newfilename."' WHERE id='".$session_id."'"

						  } else {
						  	$fileIsUpload = false;
						    //echo "Sorry, there was an error uploading your file.";
						  }
						}

					
			}

			$roomFinalRes;
			if($dataInsertSuccess)
			{
				
				$roomFinalRes = "New Room created successfully!";
			}
			else
			{
				$roomFinalRes =  "Somthing want wrong, please try again!";
			}

		
	}

	

	if(isset($_SESSION['id']))
	{
		$user_query = "SELECT * FROM accounts WHERE id = '".$_SESSION['id']."'";
		$user_query_result = mysqli_query($con,$user_query);
		while($row=mysqli_fetch_array($user_query_result))
		{
			// $firstname = $row['firstname'];
			// $last_name = $row['name'];
			// $mobile = $row['mobile'];
			// $aboutyou = $row['aboutyou'];
			// $whoAmI = $row['whoAmI'];
			// $likeToTalkAbout = $row['likeToTalkAbout'];
			// $pronouns = $row['pronouns'];
			$userProfilePicture = $row['profilePicture'];
		}

	}


	if(isset($_POST['getAllUsers']))
	{
		
		$spaceid = $_POST['spaceId'];
		

		$getAllUsersQuery = "SELECT * FROM accounts WHERE spaceId='$spaceid'";    

                         
	    $allSelResult = mysqli_query($con, $getAllUsersQuery);
	 
	   $getAllUsersList = mysqli_fetch_all($allSelResult, MYSQLI_ASSOC);
	   // echo "<pre>";
	   // print_r($getAllPairList);die;
	   echo json_encode($getAllUsersList);die;
			
	}

	

	if(isset($_POST['newPass']))
	{
		
			$uId = $_POST['uId'];
			$oldPass = $_POST['oldPass'];
			$newPass = $_POST['newPass'];

			// echo $uId;
			// echo  $oldPass;
			// echo  $newPass;


			$getOldPassQuery = "SELECT password FROM accounts WHERE id=$uId";
            $getOldPassresult = mysqli_query($con, $getOldPassQuery);
            
            $rowOldPass = mysqli_fetch_assoc($getOldPassresult);

            // print_r($rowOldPass);
           // echo $rowOldPass['password']."</br>";
            $dbOldPass = $rowOldPass['password'];
            // echo base64_decode($rowOldPass['password']);
            //echo md5($oldPass)."</br>";
            $newPasshash = password_hash($newPass, PASSWORD_DEFAULT);

            if(password_verify($oldPass, $dbOldPass))
            {
            	
            	$changePassSql = "UPDATE accounts SET password='".$newPasshash."' WHERE id=$uId";

				 
					 //$imgResult = mysqli_query($con, $removeImageSql);

					if (mysqli_query($con, $changePassSql)) {

					 // $resRemoveSuc = "password changed successfully";
					  echo 1;die;
					} else {
					  //echo "Error updating record: " . mysqli_error($conn);
						echo 0;die;
					}
            }
            else
            {
            	echo 2;die;
            }

			die;

	}

	if(isset($_POST['removeImage']))
	{

		$imageName = $_POST['removeImage'];

		$session_id = $_POST['uSession'];
		$imageInfo = "";
		$filePathInfo = $sitPath."/profileImages/".$imageName;
		
		$resRemoveSucc = "";

		
		if (file_exists($filePathInfo)) {
			
			$removeFile = unlink($filePathInfo);
				sleep(2);
				if($removeFile)
				{
					

		    	 $removeImageSql = "UPDATE accounts SET profilePicture='".$imageInfo."' WHERE id=$session_id";

				 
					 //$imgResult = mysqli_query($con, $removeImageSql);

					if (mysqli_query($con, $removeImageSql)) {

					  $resRemoveSuc = "Record updated successfully";
					  echo 1;die;
					} else {
					  //echo "Error updating record: " . mysqli_error($conn);
						echo 0;die;
					}
				}
				else
				{
					echo 0;die;
				}

					
					
    			// if($imgResult > 0)
    			// {
    			// 	$resRemoveSucc = "Profile Image Remove successfully";

    			// 	 //header('Location: '.$_SERVER['REQUEST_URI']);
    			// }
				
		  } else {
 
		  		$resErr =  "Profile Image not remove successfully";
		  		echo 0;die;
    	 }

    	//echo json_encode(['response' => "resRemoveSucc"]);die;
    	 //echo 1;exit;
    	//echo json_encode("done");die;
    	

		
	}


if(isset($_POST['submitProfile'])){
	
	 // if(empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['comments'])) {
  //           $error = true;
  //       }else{
		// 	  $sent = true;
		// }
	@$first_name = @$_POST['first_name'];
	@$last_name = @$_POST['last_name'];
	@$who_i_am = @$_POST['who_i_am'];
	@$talk_about = @$_POST['talk_about'];
	@$pronouns = @$_POST['pronouns'];
	@$mobile = @$_POST['mobile'];
	$profile_picture = @$_POST['profile_picture'];
	@$session_id = $_SESSION['id'];



	$path = getcwd();
	$target_dir = $path."/profileImages/";
	
	$target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
	$orignalFileName = basename($_FILES["profile_picture"]["name"]);
	$sql = "";
	if($orignalFileName == "")
	{

			$sql = "UPDATE accounts SET firstname='".$first_name."',name='".$last_name."',mobile='".$mobile."',whoAmI='".$who_i_am."',likeToTalkAbout='".$talk_about."',pronouns='".$pronouns."' WHERE id='".$session_id."'";

	}
	else
	{
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$fileExtensionChk = "";
		$fileIsUpload = false;
		
			
			$check = getimagesize($_FILES["profile_picture"]["tmp_name"]);



			 if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
				   $fileExtensionChk =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 0;
				} 
				else{
					$fileExtensionChk ="";
			    	$uploadOk = 1;
				}	

				$temp = explode(".", $_FILES["profile_picture"]["name"]);
				$newfilename = round(microtime(true)) . '.' . end($temp);

				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
				 $fileIsUpload = false;
				// if everything is ok, try to upload file
				} else {
				  if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_dir.$newfilename)) {
				    //echo "The file ". basename( $_FILES["profile_picture"]["name"]). " has been uploaded.";
				     $fileIsUpload = true;
				     $sql = "UPDATE accounts SET firstname='".$first_name."',name='".$last_name."',mobile='".$mobile."',whoAmI='".$who_i_am."',likeToTalkAbout='".$talk_about."',pronouns='".$pronouns."',profilePicture='".$newfilename."' WHERE id='".$session_id."'";
				  } else {
				  	$fileIsUpload = false;
				    //echo "Sorry, there was an error uploading your file.";
				  }
				}

			
	}




	if (mysqli_query($con, $sql)) {
		$_SESSION['name'] = $first_name;
		$userProfilePicture = $newfilename;
	  // echo "Record updated successfully";
	} else {
	  echo "Error updating record: " . mysqli_error($conn);
	}
	
}

?> 

<?php if($error == true){ ?>
<p class="error">Text</p>
<?php } if($sent == true) { ?>
<p class="sent">Text</p>
<?php } ?>

 <div id="myLinks" class="mylink-sidebar">
<div class="user" >
	
	<div class="Loader" style="display: none;"></div>
    <span class="image">
    	

    	<?php 
      				
			$imgpath = currentUrl."/video-chat/profileImages/".$userProfilePicture; 
		
		if(isset($userProfilePicture) && !empty($userProfilePicture))
		{     					
		?>
			<!-- <div class="defaultImgDisplay"></div>	 -->
			<img src="<?php echo $imgpath ?>"  class="userProfImg"  />
		<?php } else
		{ ?>
			<div class="defaultImgDisplay"></div>	

		<?php }?>
    				

    </span>
    <span class="user-name">
        <?php
if (!isset($_SESSION['loggedin'])) 
{?>
        <a href="../signup.php" >Sign Up</a>/<a href="../">Sign In</a>
        <?php   } 
else{
    echo "<div><span style='font-weight:700;' class='name redirectTOIndex'>  ".$_SESSION['name']." </span><span class='edit_profile_mn' data-toggle='modal' data-target='#edit_profile' data-toggle='tooltip' data-placement='right' title='Edit your profile'><i class='fa fa-edit'></i></span></div> "; ?>
        <!-- <a href="../logout.php"><i class="fa fa-sign-out-alt">
        </i>Logout</a> -->
        <?php
} 
        ?>

         <a href="javascript:void(0);" class="icon btnIcon clsClose">
    <i class="fa fa-close"></i>
  </a>
    </span>
   <!--  <br> -->
    <?php

// $query_room = "SELECT * FROM category WHERE createdBy = '".$_SESSION['id']."'";
$getPendingQuery = "SELECT id FROM category where (createdBy='".$_SESSION['id']."' or FIND_IN_SET('".$_SESSION['id']."',moderator)) and type='Private' ";

$pendingCountQuery = mysqli_query($con,$getPendingQuery);
$totalrowsOMRes = mysqli_num_rows($pendingCountQuery);

$getAllRoomList = mysqli_fetch_all($pendingCountQuery, MYSQLI_ASSOC);
$pendingCounts;
foreach ($getAllRoomList as $key => $value) {

	$selPendingQuery = "SELECT * FROM venuemembers where  VenueID='".$value['id']."' and Role='pending'";
	$execPendingRes = mysqli_query($con,$selPendingQuery); 
	 $totalrows = mysqli_num_rows($execPendingRes);
		//echo $totalrows;
		if($totalrows > 0)
		{
			$pendingCounts += $totalrows;
		}

	//print_r($execPendingRes); 
	// if()
	// {
	// 	$pendingCounts += 1;
	// }
	//print_r($value['id']);

}

//echo $pendingCounts;

// echo $count;
// $result_room = mysqli_query($con,$query_room);
// $test = 0;
// while($row=mysqli_fetch_array($result_room))
// {
//     $users = explode(",",$row['access']);
//     if($users[0])
//     {
//         $test = 1;
//         break;
//     }
// }
?>
</div>
<div class="sidebar_mn">
    

    <?php
    		// $findOwnerModeratorQ = "SELECT * FROM category where createdBy='".$_SESSION['id']."' or FIND_IN_SET('".$_SESSION['id']."',moderator)";
    		// $findOWModExec = mysqli_query($con,$findOwnerModeratorQ); 
	 		

	 	if($totalrowsOMRes > 0){	
			if($pendingCounts > 0)
			{
			    ?>   

			    <button type="button" class="pendingMemBtn">Membership requests <span class="badge badge-light"><?php echo $pendingCounts ?></span></button>    
			    <!-- <span class="red" style="margin-left: 20px;height: 10px;width:10px;background-color: red;border-radius: 50%;display: inline-block;"></span>
			    <a href="../admin.php" style="color: white; text-decoration: none;font-weight: 800;text-align: center;margin-left: 5px ">Membership requests(<?php echo $pendingCounts ?>)</a> -->
			    <?php
			}else
			{?>

				<button type="button" class="pendingMemBtn">Membership requests <span class="badge badge-light">0</span></button>
				<!-- <span class="red" style="margin-left: 20px;height: 10px;width:10px;background-color: red;border-radius: 50%;display: inline-block;"></span>
			    <a href="../admin.php" style="color: white; text-decoration: none;font-weight: 800;text-align: center;margin-left: 5px ">Membership requests(<?php echo $pendingCounts ?>)</a> -->
	<?php }} ?> 



    <?php 

    if(isset($_SESSION['id'])){ ?>
    <button type="button" class="create-room btn-create mt-3" data-toggle="modal" data-target="#createNewRoom">Create Venue</button>
<?php } 
	
	?>

	<?php 

	if($findOwnerOfSpace > 0)
	{ 
			$getPendingSpaceCountQry = "select accounts.*,validate.* 
								from validate 
								LEFT JOIN accounts ON accounts.id=validate.user_id
								where validate.spaceId='$spaceId' and validate.access='0'";
		 $getPendingSpaceCountExec = mysqli_query($con,$getPendingSpaceCountQry);
		 $getPendingSpaceCountRes = mysqli_num_rows($getPendingSpaceCountExec);
		 // $getPendingSpaceCountRes = mysqli_fetch_all($getPendingSpaceCountExec, MYSQLI_ASSOC);

	?>
		<button type="button" class="btn btn-create app-space-mem mt-3" >Space member requests <span class="badge badge-light"><?php echo  $getPendingSpaceCountRes ?></span></button>
	<?php }
	
?>

<!-- <button type="button" class="btn btn-create app-space-mem mt-3" >Space member requests <span class="badge badge-light">3</span></button> -->

	<div class="venues_container">
		<h3 class="ur_venues">
        Your Venues
    </h3>

	
	
    <ul class="sidebar_list">
        <?php
$member_venue_id = array();
$owner_venue_id = array();
// $query1 = "SELECT VenueID FROM venuemembers where UserID = '".$_SESSION['id']."' AND Role != 'left'";
$query1 = "SELECT VenueID FROM venuemembers where UserID = '".$_SESSION['id']."' AND spaceId='".$spaceId."' AND (Role != 'left' AND Role != 'pending') ";
$result1 = mysqli_query($con,$query1);
while($row1=mysqli_fetch_row($result1)){
    $member_venue_id[] = $row1[0]; 
}
// print_r($member_venue_id);
 $query2 = "SELECT id FROM category where createdBy = '".$_SESSION['id']."'";
 // $query2 = "SELECT * FROM category where createdBy='".$_SESSION['id']."' or FIND_IN_SET('".$_SESSION['id']."',members) or FIND_IN_SET('".$_SESSION['id']."',moderator)";
//$query2 = "SELECT * FROM category";
$result2 = mysqli_query($con,$query2);
while($row2=mysqli_fetch_row($result2)){
    $owner_venue_id[] = $row2[0]; 
}

$final_venue_ids = array_merge($member_venue_id,$owner_venue_id);
$final_venue_ids = array_unique($final_venue_ids);

// echo "<pre>";
// print_r($final_venue_ids);

foreach($final_venue_ids as $single_venue_id){
    // $query = "SELECT * FROM category where ID = '".$single_venue_id."'";
    // echo $single_venue_id; 
    $query = "SELECT * FROM category where ID = '".$single_venue_id."' AND spaceId='".$spaceId."' ";
    $result = mysqli_query($con,$query);
   
    while($row=mysqli_fetch_row($result))
    {
    	
    	?>
    	<ul class="sidebar-inner-ul">
        <li class="sidebarMenu txtVenueName">
            <form action="https://iungo.io/video-chat/chatroom.php" method="post">
                <input type="text" name="createroom_id" value="
<?php echo $row[0] ?>" class="chatroomId" hidden>
                <input type="text" name="createroom" value="
<?php echo $row[1] ?>" hidden>
                <input type="text" name="room_domain" value="
<?php echo $room_domain ?>" hidden>
                <input type="submit" class="" value="
<?php echo $row[1] ?>" > <?php if($row[7] == "Private")
								{ echo "<i class='fa fa-lock privLock'></i>"; }
								else
								{ echo "<i class='fa fa-users' aria-hidden='true'></i>"; }
							?>
				&nbsp; <?php 
						$membersList = $row[10];
						$membersListEx = explode(",", $membersList);
						//echo $_SESSION['id'];	
						//echo in_array($_SESSION['id'], $membersListEx);
						if($row[5] == $_SESSION['id'] || in_array($_SESSION['id'], $membersListEx)) {?><i class="fa fa-edit venueEdit" data-toggle="tooltip" data-placement="right" title="Click to Edit venue" style="display: none"></i> <?php } ?>
            </form>
        </li>
    </ul>
        <?php   }
}
        ?>
    </ul>
    </div>
    <?php 
    if (isset($_SESSION['loggedin'])) 
	{	?>
		    <div class="btn-logout">
		    	<a href="../logout.php"><!-- <i class="fa fa-sign-out-alt"></i> -->Logout </a>
		    </div>
    <?php } ?>
</div>

</div>
 <!-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script> -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" >
 <!-- <link rel="stylesheet" href="css/sidebar-style.css"> -->
 <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
 <link rel="stylesheet" href="css/sidebar-new-style.css" />
 <link rel="stylesheet" href="css/normalize.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/fontawesome.min.js" ></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->

<?php 

$user_query = "SELECT * FROM accounts WHERE id = '".$_SESSION['id']."'";
$user_query_result = mysqli_query($con,$user_query);
while($row=mysqli_fetch_array($user_query_result))
{
	$firstname = $row['firstname'];
	$last_name = $row['name'];
	$mobile = $row['mobile'];
	$aboutyou = $row['aboutyou'];
	$whoAmI = $row['whoAmI'];
	$likeToTalkAbout = $row['likeToTalkAbout'];
	$pronouns = $row['pronouns'];
	$profilePicture = $row['profilePicture'];
}
?>

<div class="modal fade" id="edit_profile" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div class="container">
      		<div class="row">
      			<div class="col-md-8">
      
				<div class="edit_profile_section_mn"> 
					<form action="" name="edit_profile_form" method="POST" enctype="multipart/form-data">
					  <div class="form-group">
						<label for="first_name" class="col-form-label">First Name:</label>
						<input type="text" name="first_name" class="form-control" id="first_name"  value="<?php echo $firstname; ?>" required >
					  </div>
					  <div class="form-group">
						<label for="last_name" class="col-form-label">Last Name:</label>
						<input type="text" name="last_name" class="form-control" id="last_name" value="<?php echo $last_name; ?>" required>
					  </div> 
					  <div class="form-group">
						<label for="who_i_am" class="col-form-label">who am I:</label>
						<textarea class="form-control" id="who_i_am" name="who_i_am"><?php echo @$whoAmI; ?></textarea>
					  </div>
					  <div class="form-group">
						<label for="talk_about" class="col-form-label">What I like to talk about:</label>
						<textarea class="form-control" id="talk_about" name="talk_about"><?php echo @$likeToTalkAbout; ?></textarea>
					  </div>
					  <div class="form-group">
						<label for="pronouns" class="col-form-label">Pronouns:</label>
						<input type="text" name="pronouns" class="form-control" id="pronouns" Placeholder="Ex: they/them/theirs, she/her/hers, he/him/his" value="<?php echo @$pronouns ?>">
					  </div>
					  <div class="form-group">
						<label for="mobile" class="col-form-label">Mobile:</label>
						<input type="number" name="mobile" class="form-control" id="mobile" value="<?php echo $mobile; ?>" >
					  </div>
					  <div class="form-group">
						<label for="profile_picture" class="col-form-label">Profile Photo:</label>
						<!-- <input type="file" name="profile_picture" class="form-control" id="profile_picture" value="test"> -->
						<div class="upload-btn-wrapper">

							<div class="btnFile">Upload an image</div>
							<input type="file" name="profile_picture" class="custom-file-input" id="profile_picture" value="test">
					  	</div>
					  </div>
					  <div>
					  		<label class="lblChangePass">Click here to change password</label>
					  </div>
					    <button type="button" class="btn editProfileClose" data-dismiss="modal">Close</button>
						<input type="submit" class="btn editProfile" name="submitProfile" id="submit" value="Submit">
					</form>
				</div>


      				
      			</div>
      			<div class="col-md-4"> 
      				
	      				<?php 
	      				
	      					$imgpath = currentUrl."/video-chat/profileImages/".$profilePicture; 
							
							if(isset($profilePicture) && !empty($profilePicture))
							{     					
	      				?>
	      					<div class="profileImg-tag">
	      					<div class="profileImg-taginner">
	      						<img src="<?php echo $imgpath ?>"  />
	      					</div>
	      				<?php } else
	      				{ ?>
	      					<!-- <img src="" height="150px" width="150px" alt="no image"/> -->

	      				<?php }?>

	      				<?php if(isset($profilePicture) && !empty($profilePicture))
	      				{ ?>
	      					<button type="button" class="btn btn-danger remProfImg" id="removeImage">Remove Photo</button></div>

	      				<?php } ?>
      				
       			</div>	
      			
      		</div>

			      		<!-- Start Modal for update password-->
			<div class="modal fade" id="changePassModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLabel">Change password</h5>
			        <button type="button" class="close changePassModalClose" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			      	<form method="post" id="frmPassReset">
					 <!--  <div class="form-group">
					    <label for="exampleInputEmail1">Email address</label>
					    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
					    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
					  </div> -->
					  <input type="hidden" id="uid" value="<?php echo $_SESSION['id']?>">
					  <div class="form-group">
					    <label for="exampleInputPassword1">Old Password</label>
					    <input type="password" class="form-control" id="oldPass" required="">
					  </div>
					  <div class="form-group">
					    <label for="exampleInputPassword2">New Password</label>
					    <input type="password" class="form-control" id="newPass" required="">
					  </div>
					  <div class="form-group">
					    <label for="exampleInputPassword3">Confirm Password</label>
					    <input type="password" class="form-control" id="confPass" required="">
					  </div>
					 
					 
					</form>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn editProfileClose changePassModalClose" >Close</button>
			        <button type="button" class="btn  changePass">Save changes</button>
			      </div>
			    </div>
			  </div>
			</div>

			      		<!-- End Modal for update password-->

      		
		</div>

      </div>
 
    </div>
  </div>
</div>


				 <!-- Start Modal for create new venue  -->


				<div class="modal fade" id="createNewRoom" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h4 class="modal-title" id="exampleModalLabelRoom">Create New Venue</h4>
				        <button type="button" class="close venueModelClose" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form action="" name="createRoom_form" method="POST" id="venueNewFrm" enctype="multipart/form-data">
				        	<input type="hidden" name="venueAction" id="venueAction" value="add" />
				        	<input type="hidden" name="venueId" id="venueId" value="0" />
				        	<div class="row">
				        	<div class="col-12">
							  <div class="form-group">
							    <label for="lblVenueName">Venue name</label>
							    <input type="text" class="form-control" id="venueName" name="venueName" placeholder="Enter a venue name" required="">
							  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
						  <div class="form-group">
						    <label for="lblVenueDesc">Venue description</label>
						    <textarea class="form-control" id="venueDesc" name="venueDesc" rows="3"  required="required"></textarea>
						  </div>
						</div>
						</div>

						  		<div class="row">
						  			<div class="col-6">
						  				<label for="lblVanueType" class="clsVenueType">Venue Type</label>
						  			</div>
						  		</div>

						  	<div class="row">
						 		 <div class="form-check">

								  		<div class="col-12">
									  <input class="form-check-input" type="radio" name="venueType" id="venueTypePub" value="public" checked >
									  <label class="form-check-label" for="rdbVPub">
									    Public &nbsp; <i class="fa fa-eye vTypE" data-toggle="tooltip" data-placement="top" title="Everybody in the Space can join your Venue"></i>
									  </label>

									</div>
									</div>
									<div class="spinner-border text-primary spindMod" role="status" style="display: none;">
									  <span class="sr-only">Loading...</span>
									</div>
									&nbsp;
									<!-- <div class="col-8"> -->
									  <div class="form-check privateRbt" >
										  <input class="form-check-input" type="radio" name="venueType" id="venueTypePriv" value="private">
											  <label class="form-check-label" for="rdbVPriv">
											    Private &nbsp; <i class="fa fa-eye vTypE"  data-toggle="tooltip" data-placement="top" title="The Venue’s moderators needs to accept new members in this Venue."></i>
											  </label>
										</div>
									<!-- </div> -->
								
									
							</div>

						  <!-- <div class="form-group">
						    <label for="formGroupExampleInput2">Another label</label>
						    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input placeholder">
						  </div> -->
						  <div class="row moderatorsLst" style="display: none">
						  	<div class="col-12">
							  	<select class="js-example-basic-multiple col-12" name="moderators[]" multiple="multiple">
								
								</select>
							</div>
						</div>
						<div class="row venueImgDiv">
							<div class="col-8">
								<div class="form-group">
							    <label for="exampleFormControlFile1">Venue picture</label>

							    <div class="upload-btn-wrapper">

									<div class="btnFile">Upload an image</div>
									<input type="file" name="venuePicture" class="custom-file-input" id="venuePicture" />
							  	</div>

							    <!-- <input type="file" class="form-control-file" id="venuePicture" name="venuePicture"> -->


							  </div>
							</div>
							<div class="col-4">
									<div class="venueImgOuter" style="display: none">
										<img src="" style="display: none" class="venueImg" />
									</div>	

							</div>
						</div>
						
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn editProfileClose venueModelClose" data-dismiss="modal">Close</button>
				        <input type="submit" class="btn createRoomBtn" name="createRoom" id="roomCreate" value="Create Room">
				        <!-- <button type="submit" class="btn btn-primary" name=""></button> -->
				      </div>
				      </form>
				    </div>
				  </div>
				</div>


  

  				 <!-- end Modal for create new venue  -->
<!-- <script>
	
		$(document).ready(function() {



              // var firstName = $('#first_name').val();
              // var lastName = $('#last_name').val();
              // var intials = $('#first_name').val().charAt(0) + $('#last_name').val().charAt(0);
              // console.log(intials);
              // var profileImage = $('.defaultImgDisplay').text(intials);
              // console.log(profileImage);
              // $(document).on('hidden.bs.modal', '.modal', function () {
              //     $('.modal:visible').length && $(document.body).addClass('modal-open');
              // });










		   
		   // $('#removeImage').on('click',function(){
		   // 		var imageName =  '<?php echo $profilePicture ?>';

		   // 		console.log(imageName);
		   		
		   // 		 jQuery.ajax({
	    //                     type:'POST',
	    //                     data:{removeImage:imageName},
	    //                     success:function(data){
	    //                     	//alert(data);
	    //                     	console.log(data);
	    //                     	 //location.reload(true);
	    //                      }
     //                    });

		   // });



		});

</script> -->
	
	<!--Start modal for open full screen img -->

			<div id="modal01" class="w3-modal" onclick="this.style.display='none'">
			  <span class="w3-button w3-hover-red w3-xlarge w3-display-topright">&times;</span>
			  <div class="w3-modal-content w3-animate-zoom">
			    <img id="img01" style="width:100%">
			  </div>
			</div>
	<!--End modal for open full screen img -->

<script>
	function onClick(element) {
  document.getElementById("img01").src = element.src;
  document.getElementById("modal01").style.display = "block";
}
</script>

<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>

<script>

            $(document).ready(function(){

            	

              var firstName = $('#first_name').val();
              var lastName = $('#last_name').val();
              var intials = $('#first_name').val().charAt(0) + $('#last_name').val().charAt(0);
              //console.log(intials);
              var profileImage = $('.defaultImgDisplay').text(intials.toUpperCase());
              //var profileLiveUimage = $('.userLiveName').text(intials.toUpperCase());
              var spaceId = '<?php echo $_SESSION['spaceId']?>';
              console.log(spaceId);

               $('.js-example-basic-multiple').select2();
               $('select').select2({
				  placeholder: 'Select moderators',
				  allowClear: true
				});	



               $('.btnIcon').on('click',function(){
               		 var x = document.getElementById("myLinks");
               		 
					  if (x.style.display === "block") {
					    x.style.display = "none";
					   
					  } else {
					    x.style.display = "block";
					    x.style.transition = "0.2s ease-in-out";
					  }
               		// console.log("asddddddddd");
               });

               var chatroomID;
              	// $('#createNewRoom').modal({backdrop: 'static', keyboard: false});  

		     	 $(".sidebarMenu").hover(function(){
				    $(this).find(".venueEdit").toggle();
				    chatroomID = $(this).find(".chatroomId").val();

				});

		     	 $('.pendingMemBtn').on('click',function(){
		     	 		
		     	 		window.location.href = "moderator.php";
		     	 		
		     	 });


		     	 $('.app-space-mem').on('click',function(){
		     	 		
		     	 		window.location.href = "spaceRequest.php";
		     	 });

		     	 $('.redirectTOIndex').on('click',function(){

		     	 		window.location.href = "index.php";
		     	 });

		     	  $('.ur_venues').on('click',function(){

		     	 		window.location.href = "index.php";
		     	 });


		     	 $('.venueEdit').on('click',function(){
              		//$('.custom-scrollbar').css('position','inherit');

		     	 	// console.log(chatroomID);
		     	 		$("#venueAction").val('update');
		     	 		$("#venueId").val(chatroomID);
		     	 		$("#exampleModalLabelRoom").text("Edit Venue");
		     	 		$("#roomCreate").val("Edit Venue");


		     	 		$.ajax({
                                type:'POST',
                                url:'sidebar.php',
                                data:{'venueIdd':chatroomID},
                                 beforeSend: function() {
					              $(".Loader").show();
					           },
                                success:function(data){
                                    //alert(data);
                                    //console.log(data);
	                                    if(data)
	                                    {

	                                    	var sessionId = '<?php echo $_SESSION['id'] ?>';

	                                        var getRoomInfo = jQuery.parseJSON(data);
	                                        console.log(getRoomInfo);
	                                      	var roomInfoLength =  Object.keys(getRoomInfo).length;
	                                      	$('.js-example-basic-multiple').html("");
	                                      	$.each(getRoomInfo, function( key, value ) {
		     	 								$("#venueName").val(value.Name);
		     	 								$("#venueDesc").val(value.description);
	     	 									if(value.type == "Private")
	     	 									{
             										$('.moderatorsLst').css('display','block');

	     	 										$("#venueTypePriv").prop("checked", true);

	     	 										var getAllUsersLst = '<?php 
	     	 										$spaceID =  $_SESSION['spaceId'];
                                       				$getAllUsersQuery = "SELECT id,firstname,name,profilePicture FROM accounts where spaceId='$spaceID'";    

                         
												    $allSelResult = mysqli_query($con, $getAllUsersQuery);
												 
												   $getAllUsersList = mysqli_fetch_all($allSelResult, MYSQLI_ASSOC);
												  
												   echo json_encode($getAllUsersList);

                                       			?>';

                                       				console.log(value.moderator);
                                       				var resMembers=0;
                                       				if(value.moderator != "" && value.moderator != null)
                                       				{
	                                       				 resMembers = value.moderator.split(",");
	                                       			}
	                                       			var objAllUsers = jQuery.parseJSON(getAllUsersLst);
	                                       			
	                                       			var i;

	                                       			for (i = 0; i < resMembers.length; i++) {
	                                       					

		                                       			$.each( objAllUsers, function( keyuser, valueuser ) {
		                                       					
		                                       				if(resMembers[i] == objAllUsers[keyuser].id)
		                                      				{
		                                      					
		                                      					sel = 'selected="selected"';

			                                   					$('.js-example-basic-multiple').append('<option '+sel+' value="'+objAllUsers[keyuser].id+'">'+
						                                   	objAllUsers[keyuser].firstname +" "+ objAllUsers[keyuser].name +'</option>');

					                                   		}	
		                                       				
		                                       			});
	                                       			}


	                                       			$.each( objAllUsers, function( keyuser, valueuser ) {
		                                       					
		                                       				if(sessionId != objAllUsers[keyuser].id)
		                                      				{
		                                      					
		                                      					$('.js-example-basic-multiple').append('<option value="'+objAllUsers[keyuser].id+'">'+
						                                   	objAllUsers[keyuser].firstname +" "+ objAllUsers[keyuser].name +'</option>');

					                                   		}	
		                                       				
		                                       			});

	                                       			

	     	 									}
	     	 									else
	     	 									{
	     	 										$("#venueTypePub").prop("checked", true);
             										$('.moderatorsLst').css('display','none');

	     	 									}
                                       			
                                       			
                                       			//console.log(value.Name);

                                       			if(value.venueImage == "" || value.venueImage == null)
                                       			{
             										$('.venueImg').css('display','none');
             										$('.venueImgOuter').css('display','none');

                                       				
                                       			}
                                       			else
                                       			{
                                       				
                                       				var imgname = '<?php echo  currentUrl."/video-chat/venueImages/" ?>' + value.venueImage;
                                       				$('.venueImg').replaceWith('<img src="'+imgname+'" class="venueImg"  alt="..." />');
             										$('.venueImg').css('display','block');
             										$('.venueImgOuter').css('display','block');

                                       			}
                                          
                                        	});
	                                      	//console.log(getRoomInfo.Name);
	                                      	$('#createNewRoom').modal('show');
	                                      	$(".Loader").hide();
	                                    }
                                     
                                    }
                                    
                                 
                            });

		     	 				
		     	 });


		     	 $('.venueModelClose').on('click',function(){
		     	 	// console.log("model clo");
		     	 	//$('.custom-scrollbar').css('position','sticky');
		     	 	$('#venueNewFrm').trigger("reset");
		     	 		$("#exampleModalLabelRoom").text("Create New Venue");
		     	 		$("#roomCreate").val("Create New venue");
		     	 		$('.venueImg').css('display','none');
             			$('.venueImgOuter').css('display','none');

		     	 	// $(".js-example-basic-multiple").select2("val", "");
		     	 	$(".js-example-basic-multiple").val('').trigger('change'); 

		     	 });

              $('#venueTypePriv').on('click',function(){
              			var currentUserId =  '<?php echo $_SESSION['id'] ?>';
             		$('.spindMod').css('display','block');
              				
              		$.ajax({
                            type:'POST',
                            url:'sidebar.php',
                            data:{'getAllUsers':'getAllUsers','spaceId':spaceId},
                            success:function(data){
                                //alert(data);
                                // console.log(data);

                                   if(data)
                                    {
                                    	
                                     	 $('.js-example-basic-multiple').html("");
             					$('.moderatorsLst').css('display','block');

                                       var allUserInfo = jQuery.parseJSON(data);
                                      var infoLength =  Object.keys(allUserInfo).length;
                                      
          							// $(".js-example-basic-multiple").select2({
										// 	templateResult: function (idioma) {
										//   	var $span = $("<span><img src='https://www.free-country-flags.com/countries/"+idioma.id+"/1/tiny/" + idioma.id + ".png'/> " + idioma.text + "</span>");
										//   	return $span;
										//   },
										// 	templateSelection: function (idioma) {
										//   	var $span = $("<span><img src='https://www.free-country-flags.com/countries/"+idioma.id+"/1/tiny/" + idioma.id + ".png'/> " + idioma.text + "</span>");
										//   	return $span;
										//   }
										// });

                                       $.each( allUserInfo, function( key, value ) {
                                       	// console.log(allUserInfo[key].id);

                                       	var imgname = '<?php echo  currentUrl."/video-chat/profileImages/" ?>' + allUserInfo[key].profilePicture; 
                                       	// console.log(imgname);

                                       	if(allUserInfo[key].profilePicture == "" || allUserInfo[key].profilePicture == null)
                                         {

                                         }
                                         else
                                         {

           										// console.log(allUserInfo[key].firstname +" "+ allUserInfo[key].name);
           										// $(".js-example-basic-multiple").select2({
											// 	templateResult: function (idioma) {
											//   	var $span = $("<span><img src='"+imgname+"' height='50px' width='50px'/> " + allUserInfo[key].firstname +" "+ allUserInfo[key].name +"</span>");
											//   	return $span;
											//   },
											// 	templateSelection: function (idioma) {
											//   	var $span = $("<span><img src='"+imgname+"' height='50px' width='50px'/> "+ allUserInfo[key].firstname +" "+ allUserInfo[key].name +"</span>");
											//   	return $span;
											//   }
											// });




                                   	// 		$('.js-example-basic-multiple').append('<option value="'+allUserInfo[key].id+'"><img src="'+imgname+'" height="50px" width="50px" style="display: inline-block;" />'+
                                   	// allUserInfo[key].firstname +" "+ allUserInfo[key].name +'</option>');

                                   	 }

                                   	 if(currentUserId != allUserInfo[key].id)
                                      {
	                                   	$('.js-example-basic-multiple').append('<option value="'+allUserInfo[key].id+'">'+
	                                   	allUserInfo[key].firstname +" "+ allUserInfo[key].name +'</option>');

                                   		}
                                          
                                    });
                                          	
                                       $('.spindMod').css('display','none'); 
                                        
                                    }
                                    else
                                    {
                                      // $('.liveUserHeading').css('display','none');
                                      // $('.liveUserInfoCls').css('display','none');
                                    }
                                     
                                 //location.reload(true);
                                }
                                
                             
                        });




              });

               $('#venueTypePub').on('click',function(){
              	
             		$('.moderatorsLst').css('display','none');
                    $('.spindMod').css('display','none'); 


              });

              $('.create-room').on('click',function(){
              		//$('.custom-scrollbar').css('position','inherit');
              		console.log("calll");
              });

                $('.edit_profile_mn').on('click',function(){
              		//$('.custom-scrollbar').css('position','inherit');
              		console.log("calll");
              });

              

              $(document).on('hidden.bs.modal', '.modal', function () {
                  $('.modal:visible').length && $(document.body).addClass('modal-open');
              });


				$(".custom_again_join").click(function(){
				    $(this).parent().parent().find(".already_joined").val('no');
				     $(this).parent().parent().submit();  
				});


          $('.lblChangePass').on('click',function(){
             

              $('#changePassModal').modal('show');

          });



          $('.changePassModalClose').on('click',function(){
             
              $('#changePassModal').modal('toggle');

          });

          $('.changePass').on('click',function(){
             
            
            var oldPass = $('#oldPass').val();
            var newPass = $('#newPass').val();
            var confPass = $('#confPass').val();
            var uid = $('#uid').val();
            
            
            if(oldPass == "")
            {
              swal("Info!", "Please enter old password!", "info");

            }
            else if(newPass == "")
            {
              swal("Info!", "Please enter new password!", "info");

            }
            else if(confPass == "")
            {
              swal("Info!", "Please enter confirm password!", "info");

            }
            else if(newPass != confPass)
            {
              swal("Error!", "New password and Confirm password are not match!", "error");

            }
            else
            {
                
                swal({
                        title: "Are you sure?",
                        text: "Once Changed, you will not be able to recover this old password!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                      })
                      .then((willDelete) => {
                        if (willDelete) {

                          $.ajax({
                                  type:'POST',
                                  url:'sidebar.php',
                                  data:{'newPass':newPass,'oldPass':oldPass,'uId' : uid},
                                  success:function(data){
                                      //alert(data);
                                      //console.log(data);
                                              if(data == 1)
                                              {
                                                  
                                                  swal("Success!", "Your password changed successfully!", "success");
                                                  $('#frmPassReset').trigger("reset");
                                                  $('#changePassModal').modal('toggle');
                                                  // setInterval(function(){ 

                                                     

                                                  //  }, 2000);
                                              }
                                              else if(data == 2)
                                              {

                                                 swal("Error!", "Your old password is incorrect!", "error");

                                                
                                                  
                                              }
                                              else
                                              {
                                                
                                                  swal("Error!", "Somthing wrong please try again!", "error");
                                                  $('#frmPassReset').trigger("reset");

                                               
                                                  // setInterval(function(){ 

                                                  //  }, 2000);

                                                  $('#changePassModal').modal('toggle');

                                                  //setInterval(function(){ location.reload(true); }, 3000);
                                              }
                                       //location.reload(true);
                                      }
                                      
                                   
                              });
                         
                        } else {
                          swal("Your current password is safe!");
                        }
                      });
                
            }

              //$('#changePassModal').modal('toggle');

          });




          
           
                       $('#removeImage').on('click',function(){
                        //alert("asdasd");
                      
                            var imageName =  '<?php echo $profilePicture; ?>';
                            var uSession = '<?php echo $_SESSION['id']; ?>';

                            // console.log(imageName);
                            // console.log(uSession);


                            swal({
                              title: "Are you sure?",
                              text: "Once deleted, you will not be able to recover this imaginary file!",
                              icon: "warning",
                              buttons: true,
                              dangerMode: true,
                            })
                            .then((willDelete) => {
                              if (willDelete) {

                                $.ajax({
                                        type:'POST',
                                        url:'sidebar.php',
                                        data:{'removeImage':imageName,'uSession' : uSession},
                                         beforeSend: function() {
							              $(".Loader").show();
							           },
                                        success:function(data){
                                            //alert(data);
                                            console.log(data);
                                             $(".Loader").hide();
                                                    if(data == 1)
                                                    {

                                                        console.log("iff inn");
                                                        swal("Success!", "Your profile image removed successfully!", "success");
                                                         setInterval(function(){ location.reload(true); }, 3000);
                                                    }
                                                    else
                                                    {
                                                        swal("Error!", "Somthing wrong please try again!", "error");

                                                        console.log("else in");
                                                         setInterval(function(){ location.reload(true); }, 3000);

                                                    }
                                             //location.reload(true);
                                            }
                                            
                                         
                                    });
                               
                              } else {
                                swal("Your profile image is safe!");
                              }
                            });
                                                        
                             // $.ajax({
                             //            type:'POST',
                             //            url:'sidebar.php',
                             //            data:{'removeImage':imageName,'uSession' : uSession},
                             //            success:function(data){
                             //                //alert(data);
                             //                console.log(data);
                             //                        if(data == 1)
                             //                        {
                             //                            console.log("iff inn");
                             //                            swal("Success!", "Your profile image removed successfully!", "success");
                             //                            setInterval(function(){ location.reload(true); }, 3000);
                             //                        }
                             //                        else
                             //                        {
                             //                            swal("Error!", "Somthing wrong please try again!", "error");

                             //                            console.log("else in");
                             //                            setInterval(function(){ location.reload(true); }, 3000);

                             //                        }
                             //                 //location.reload(true);
                             //                }
                                            
                                         
                             //        });

                    });
               
			});
        </script>