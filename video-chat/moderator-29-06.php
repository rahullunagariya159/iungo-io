<?php 

ini_set('session.cookie_domain', '.iungo.io' );
session_start();
// Change this to your connection info.
include '../connect.php';
// include 'videoChatConstant.php';

if(!isset($_SESSION['id']))
{
  header("location:../index.php");
}

if(isset($_POST['pendingUserID'])){

		$pendingUserID = $_POST['pendingUserID'];
		$pendingVenueId = $_POST['pendingVenueId'];	
		$roleStatus = $_POST['roleStatus'];	

		if($roleStatus == "approve")
		{
			
			$updApproveQry = "UPDATE venuemembers SET Role='member',status='approve' WHERE VenueID='$pendingVenueId' AND UserID='$pendingUserID'";

			// if request is approved then it's add that member in category table

			$querymembers = "SELECT members FROM category WHERE id='$pendingVenueId'";
			$resultmembers = mysqli_query($con,$querymembers);
			$rowmembers = mysqli_fetch_row($resultmembers);
			$members = explode(',', $rowmembers[0]);
			
			$count = COUNT($members)-1;
			if (in_array($pendingUserID,$members, TRUE))
			{
			}
			else{
				$membersstr = implode(",",$members);
				$membersup = $membersstr."".$pendingUserID.",";
				$querymemupdate = "UPDATE category SET members = '".$membersup."' WHERE id='$pendingVenueId'";
				mysqli_query($con,$querymemupdate);
				$count = $count + 1;
			}

			$querymemupdate = "UPDATE category SET totalusers = '".$count."' WHERE id='$pendingVenueId'";
			mysqli_query($con,$querymemupdate);

		}
		else if($roleStatus == "denied")
		{
			$updApproveQry = "UPDATE venuemembers SET Role='denied',status='denied' WHERE VenueID='$pendingVenueId' AND UserID='$pendingUserID'";
		}
		else if($roleStatus == "blocked")
		{
			$updApproveQry = "UPDATE venuemembers SET Role='blocked',status='blocked' WHERE VenueID='$pendingVenueId' AND UserID='$pendingUserID'";
		}

		if(mysqli_query($con, $updApproveQry)){
			echo 1;
		}
		else
		{
			echo 0;
		}

		
		die;	
}


if(isset($_POST['userSession']))
{	

	$userSession = $_POST['userSession'];  
	// $findModeratorQuery = "SELECT * FROM category where (createdBy='".$_SESSION['id']."' or FIND_IN_SET('".$_SESSION['id']."',moderator)) and type='Private' ";
	$findModeratorQuery = "	SELECT venuemembers.*,accounts.* 
					from venuemembers
					LEFT JOIN accounts ON accounts.id=venuemembers.UserID
					WHERE  venuemembers.VenueID IN (SELECT id FROM category where (createdBy='".$_SESSION['id']."' or FIND_IN_SET('".$_SESSION['id']."',moderator)) and type='Private') AND venuemembers.Role='pending' ";

	$findModeratorExec = mysqli_query($con,$findModeratorQuery);
	$totalrowsOMRes = mysqli_num_rows($findModeratorExec);

	$getAllModeratorList = mysqli_fetch_all($findModeratorExec, MYSQLI_ASSOC);


	$selPendingCatListQuery = "	SELECT DISTINCT(category.Name),category.id 
							from venuemembers
							JOIN accounts ON accounts.id=venuemembers.UserID
							JOIN category ON category.id=venuemembers.VenueID
							WHERE  venuemembers.VenueID IN (SELECT id FROM category where (createdBy='".$_SESSION['id']."' or FIND_IN_SET('".$_SESSION['id']."',moderator)) and type='Private') AND venuemembers.Role='pending' ";

	$execPendingCatListRes = mysqli_query($con,$selPendingCatListQuery);
	$getAllpendingCatList = mysqli_fetch_all($execPendingCatListRes, MYSQLI_ASSOC);
	
	

	// $VenueDetails = array();
 //    $userDetails = array();
	// foreach ($getAllModeratorList as $key => $value) {

	// 			$selPendingUserQuery = "SELECT * FROM venuemembers where  VenueID='".$value['id']."' and Role='pending'";
	// 			$execPendingUserRes = mysqli_query($con,$selPendingUserQuery);
	// 			$getAllpendingVenuemem = mysqli_fetch_all($execPendingUserRes, MYSQLI_ASSOC);


	// 			foreach ($getAllpendingVenuemem as $venuekey => $venuevalue) {
	// 				# code...
	// 				//print_r($venuevalue);
	// 				array_push($VenueDetails, $venuevalue);
	// 			}
	// 			//array_push($VenueDetails, $getAllpendingVenuemem);
	// 		}


	// 	foreach ($VenueDetails as $key => $value) {

	// 		//print_r($value);
	// 		$selPendingUserListQuery = "SELECT * FROM accounts where  id='".$value['UserID']."' ";
	// 		$execPendingUserListRes = mysqli_query($con,$selPendingUserListQuery);
	// 		$getAllpendingUserList = mysqli_fetch_all($execPendingUserListRes, MYSQLI_ASSOC);


	// 			foreach ($getAllpendingUserList as $userkey => $uservalue) {
	// 				# code...
	// 				array_push($userDetails, $uservalue);
	// 			}
	// 		# code...
	// 	}

		// print_r($VenueDetails);
		// print_r($userDetails);
		echo json_encode(['pendingDetails'=>$getAllModeratorList,'roomDetails'=>$getAllpendingCatList]);die;
		
}

		
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Video Chat.">
    <meta name="author" content="Brian Mau">
    <link href="/css/main.css" rel="stylesheet">
    <link href="./css/moderator-style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
	<title>Video Chat</title>
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
	 <div class="fluid-container">
       <div class="row mainrow">
           <div class="col-3">
						<?php include_once('sidebar.php'); ?>
			</div>
			 <div class="col-9">	
			 		<div>
			 			<a href="index.php" class="backBtn"><i class="fa fa-backward"></i> <span>Back</span></a>
			 		</div>
			 		<div class="pendingUsersList">
			 			
			 		  <!-- List of all pending venue -->
					</div>

			 </div>
		</div>
	</div>

</body>

 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

   <script>
   			$(document).ready(function(){

   				
   				var userSession = '<?php echo $_SESSION['id'] ?>';


   				//console.log(userSession);

   					// get the result of pending user 

   				$.ajax({
	                    type:'POST',
	                    url:'moderator.php',
	                    data:{'userSession' : userSession},
	                    success:function(data){
		                       console.log(Object.keys(data).length);
		                        if(data){

		                        var response = jQuery.parseJSON(data);

		                        	console.log(Object.keys(response).length);
		                        var pendingDetails = response.pendingDetails;
		                        var roomDetails = response.roomDetails;
		                        var infoLength =  Object.keys(pendingDetails).length;
		                         //console.log(infoLength);
		                         console.log(pendingDetails);
		                        console.log(roomDetails);
		                        var j;
		                        $('.pendingUsersList').html("");
		                        if(infoLength == "")
		                        {
		                        	console.log("iff innn");
		                        	$(".pendingUsersList").append('<div class="pendingReq">No pending requests. <a href="./index.php">Return to venues</a></div>');
		                        }
		                        else
		                        {	

	                        		  $.each( roomDetails, function( key, value ) {
                                   console.log("mai in");
                                    $(".pendingUsersList").append('<div class="container header-saction mt-3"><div class="row card p-3 mainHeader"><div class="header"><ul><li>Venue Name : '+roomDetails[key].Name+'</li></ul><div class="divder"></div></div>');
                                 

		                                $.each( pendingDetails, function( userkey, uservalue ) {
		                                   	 if(roomDetails[key].id == pendingDetails[userkey].VenueID)
		                                       {
		                                       
		                                       	if(pendingDetails[userkey].profilePicture == "" || pendingDetails[userkey].profilePicture == null)
		                                         {
		                                         	var getVal = pendingDetails[userkey].firstname.charAt(0) + pendingDetails[userkey].name.charAt(0);
		                                                 
		                                              var profileImageText = getVal.toUpperCase();
		                                           
		                                             $(".pendingUsersList").append('<div class="header mt-3 getInfoPending"><input type="hidden" name="userId" class="userId" value="'+pendingDetails[userkey].UserID+'" /><input type="hidden" name="venueId" class="venueId" value="'+roomDetails[key].id+'"/><ul><li><span class="pendingUserTxt rounded-circle">'+profileImageText+' </span> <span class="cust_name ml-2">'+pendingDetails[userkey].firstname +" "+ pendingDetails[userkey].name+'</span></li><li><button type="button" class="btn btn-light btnChangeStatus active approve"><i class="fa fa-check mr-1"></i>Approvel</button><button type="button" class="btn btn-light ignore btnChangeStatus"><i class="fa fa-times mr-1"></i>Ignore</button><button type="button" class="btn btn-danger block"><i class="fa fa-ban m-1"></i>Block</button></li></ul></div><hr></div></div>');
		                                         }
		                                         else
		                                         {

		                                         	var pendingImgname = '<?php echo  currentUrl."/video-chat/profileImages/" ?>' + pendingDetails[userkey].profilePicture; 
		                                       		
		                                       		$(".pendingUsersList").append('<div class="header mt-3 getInfoPending"><input type="hidden" name="userId" class="userId" value="'+pendingDetails[userkey].UserID+'" /><input type="hidden" name="venueId" class="venueId" value="'+roomDetails[key].id+'"/><ul><li><img src="'+pendingImgname+'" alt="img" class="userImg rounded-circle"> <span class="cust_name ml-2">'+pendingDetails[userkey].firstname +" "+ pendingDetails[userkey].name+'</span></li><li><button type="button" class="btn btn-light btnChangeStatus active approve"><i class="fa fa-check mr-1"></i>Approve</button><button type="button" class="btn btn-light  ignore btnChangeStatus"><i class="fa fa-times mr-1"></i>Ignore</button><button type="button" class="btn btn-danger block"><i class="fa fa-ban m-1"></i>Block</button></li></ul></div><hr></div></div>');
		                                       	 }
		                                       }
										});
									});

		                        }

		                        
		                        
		                       

		                        }
		                        else
		                        {
		                        	console.log("else in");
		                        	$(".pendingUsersList").append('<div>No pending requests. <a>Return to venues</a></div>');
		                        }
	                       
	                        }
	               		 });

			


				function changeUserVenueStatus(userID,venueId,roleStatus){
						console.log(userID);
						console.log(venueId);
						console.log(roleStatus);

					 
                          $.ajax({
                                  type:'POST',
                                  url:'moderator.php',
                                  data:{'pendingUserID':userID,'pendingVenueId':venueId,'roleStatus':roleStatus},
                                  success:function(data){
                                      //alert(data);
                                      console.log(data);
                                      if(data == 1)
                                      {
                                      
                                      	swal("Success!", "", "success");
                                      	
                                      	setInterval(function(){ 
											location.reload();
										}, 500);
                                      }
                                      else
                                      {
                                      	swal("Error!", "something want wrong,Please try again!", "error");

                                      
                                      	
                                      	setInterval(function(){ 
										 	location.reload();
										 }, 800);
                                      }

                                      //console.log(data);
                                              
                                       //location.reload(true);
                                    }
                                      
                                   
                              });
                        }


				function blockUserStatus(userID,venueId,roleStatus){

					swal({
                        title: "Are you sure?",
                        text: "",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                      })
                      .then((willDelete) => {
                        if (willDelete) {

                          $.ajax({
                                  type:'POST',
                                  url:'moderator.php',
                                  data:{'pendingUserID':userID,'pendingVenueId':venueId,'roleStatus':roleStatus},
                                  success:function(data){
                                      //alert(data);
                                      console.log(data);
                                      if(data == 1)
                                      {
                                      
                                      	swal("Success!", "", "success");
                                      	
                                      	setInterval(function(){ 
											location.reload();
										}, 500);
                                      }
                                      else
                                      {
                                      	swal("Error!", "something want wrong,Please try again!", "error");

                                      
                                      	
                                      	setInterval(function(){ 
										 	location.reload();
										 }, 800);
                                      }

                                      //console.log(data);
                                              
                                       //location.reload(true);
                                    }
                                      
                                   
                              });
                         
	                        } else {
	                          swal("Can't change status!");
	                        }
                        });

				}

				$(document).on("click", ".approve", function(){
					
					 var userAppId = $(this).parents('.getInfoPending').find('.userId').val();
					 var venuAppId = $(this).parents('.getInfoPending').find('.venueId').val();

					changeUserVenueStatus(userAppId,venuAppId,"approve");

				
				});

				$(document).on("click", ".ignore", function(){
					
					 var userIgoId = $(this).parents('.getInfoPending').find('.userId').val();
					 var venuIgoId = $(this).parents('.getInfoPending').find('.venueId').val();
					 changeUserVenueStatus(userIgoId,venuIgoId,"denied");
				
				});

				$(document).on("click", ".block", function(){
					
					 var userBlockId = $(this).parents('.getInfoPending').find('.userId').val();
					 var venuBlockId = $(this).parents('.getInfoPending').find('.venueId').val();

					 blockUserStatus(userBlockId,venuBlockId,"blocked");
					
				});
					
   			});

			



			// $(".approve").on('click',function(){
			// 			console.log("click");
			// 				// console.log($(this).find(".userId").val());
			// 				// console.log($(this).find(".venueId").val());

			// });

   </script>

</html>

