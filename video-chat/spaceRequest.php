<?php 
	//echo "innnnnnnnnnnnnnnn";

	ini_set('session.cookie_domain', '.iungo.io' );
	session_start();
	// Change this to your connection info.
	include '../connect.php';
	// include 'videoChatConstant.php';

	if(!isset($_SESSION['id']))
	{
	  header("location:../index.php");
	}

	@$spaceId = @$_SESSION['spaceId'];
	$userId =  @$_SESSION['id']; 


	//Note : 
		// 3 = signup with diffrent email and approved by owner of organizaion(validate table)
		// 2 = signup with diffrent email and  not approved by owner of organizaion(validate table) 
		// 1 = signup with space email (account table) 
		// 0 = signup with diffrent email and request is pending (validate table)



	if(@$spaceId && isset($_POST['userownerId']))
	{


		 $getPendingSpaceQry = "select accounts.*,validate.* 
								from validate 
								LEFT JOIN accounts ON accounts.id=validate.user_id
								where validate.spaceId='$spaceId' and validate.access='0'";
		 $getPendingSpaceExec = mysqli_query($con,$getPendingSpaceQry);
		 $getPendingSpaceRes = mysqli_fetch_all($getPendingSpaceExec, MYSQLI_ASSOC);
		 //$getPendingSpaceRes = mysqli_fetch_assoc($getPendingSpaceExec);

		 // print_r($getPendingSpaceRes);
		 // die;
		 echo json_encode($getPendingSpaceRes);die;
	}

	if(isset($_POST['pendingValidateId']))
	{
		$pendingValidateId = $_POST['pendingValidateId'];
		$pendingSpaceId = $_POST['pendingSpaceId'];
		$pendingUserID = $_POST['pendingUserID'];
		$status = $_POST['status'];
		$updValidateQry;
		if($status == "approve")
		{

			$updValidateQry = "UPDATE validate SET access=1 WHERE id='$pendingValidateId' AND user_id='$pendingUserID' AND spaceId='$pendingSpaceId'";
		}
		else if($status == "canceled"){
				$updValidateQry = "UPDATE validate SET access=2 WHERE id='$pendingValidateId' AND user_id='$pendingUserID' AND spaceId='$pendingSpaceId'";
		}	

		if(mysqli_query($con, $updValidateQry)){
			echo 1;
		}
		else
		{
			echo 0;
		}
		
		die;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Video Chat.">
    <meta name="author" content="Brian Mau">
    <!-- <link href="/css/main.css" rel="stylesheet"> -->
    <link href="./css/space-request.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   <!--  <link rel="stylesheet" href="css/style.css"> -->
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
           <div class="custom-scrollbar" >
						<?php include_once('sidebar.php'); ?>
			</div>
			 <div class="" id="mylist">	
			 	<a href="javascript:void(0);" class="icon btnIcon">
                    <i class="fa fa-bars"></i>
                  </a>


                  <div class="top-moderator-header d-flex align-items-center justify-content-between">
	                <div class="join font-weight-bold"><a href="index.php" class="backBtn"><i class="fa fa-backward"></i> <span>Back</span></a></div>
	                <div class="search_button_header d-flex align-items-center">
	                 
	                  List of request to join the space
	                </div>
	              </div>

			 	<!-- 	<div class="prev-button">
			 			<a href="index.php" class="backBtn"><i class="fa fa-backward"></i> <span>Back</span></a>
			 		</div>
			 		<div class="space-request">List of request to join the space </div> -->

			 		<div class="pendingSpaceMemList">
			 				
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

   				
   				var userownerId = '<?php echo $_SESSION['id'] ?>';
   					

              $.ajax({
                      type:'POST',
                      url:'spaceRequest.php',
                      data:{'userownerId':userownerId},
                      success:function(data){
                          //alert(data);
       	                 //console.log(data);

                    	if(data)
                    	{
                    		$('.pendingSpaceMemList').html("");
                    		var pendingSpaceRes = jQuery.parseJSON(data);
                    			console.log(pendingSpaceRes);

                    		var infoLength =  Object.keys(pendingSpaceRes).length;
                    		//console.log(infoLength);
                    		var j=0;
                    		if(infoLength == "")
	                        {
	                        	console.log("iff innn");
	                        	$(".pendingSpaceMemList").append('<div class="pendingReq">No pending requests. <a href="./index.php">Return to venues</a></div>');
	                        }
	                        else
	                        {
                    		$.each( pendingSpaceRes, function( userkey, value ) {

                    			if(pendingSpaceRes[userkey].profilePicture == "" || pendingSpaceRes[userkey].profilePicture == null)
                                 {	

                                 	// var getVal = pendingSpaceRes[userkey].firstname.charAt(0) + pendingSpaceRes[userkey].name.charAt(0);
                                         
                                  //     var profileImageText = getVal.toUpperCase();
                                   
                                   var pendingImgname = '<?php echo  currentUrl."/video-chat/profileImages/defaultUser.png" ?>';

                                     $(".pendingSpaceMemList").append('<div class="header mt-3 getInfoPending"><input type="hidden" name="userId" class="userId" value="'+pendingSpaceRes[userkey].user_id+'" /><input type="hidden" name="validateId" class="validateId" value="'+pendingSpaceRes[userkey].id+'"/><input type="hidden" name="spaceId" class="spaceId" value="'+pendingSpaceRes[userkey].spaceId+'"/><ul class="space-req-ul"><div class="col-lg-7"><li><img src="'+pendingImgname+'" alt="img" class="userImg rounded-circle"> <span class="cust_name ml-2">'+pendingSpaceRes[userkey].firstname +" "+ pendingSpaceRes[userkey].name+'</span><p class="space-reason"> Reason : '+pendingSpaceRes[userkey].reason+'</p></li></div><div class="col-lg-5 text-right"><li><button type="button" class="btn btn-light btnChangeStatus active approve"><i class="fa fa-check mr-1"></i>Approve</button><button type="button" class="btn btn-danger canceled"><i class="fa fa-times m-1"></i>Canceled</button></li></div></ul></div><hr class="spaceReq-hr" /></div></div>');
                                 }
                                 else
                                 {

                                 	var pendingImgname = '<?php echo  currentUrl."/video-chat/profileImages/" ?>' + pendingSpaceRes[userkey].profilePicture; 
                               		
                               		$(".pendingSpaceMemList").append('<div class="header mt-3 getInfoPending"><input type="hidden" name="userId" class="userId" value="'+pendingSpaceRes[userkey].UserID+'" /><input type="hidden" name="validateId" class="validateId" value="'+pendingSpaceRes[userkey].id+'"/><input type="hidden" name="spaceId" class="spaceId" value="'+pendingSpaceRes[userkey].spaceId+'"/><ul class="space-req-ul"><div class="col-lg-7"><li><img src="'+pendingImgname+'" alt="img" class="userImg rounded-circle"> <span class="cust_name ml-2">'+pendingSpaceRes[userkey].firstname +" "+ pendingSpaceRes[userkey].name+'</span><p class="space-reason"> Reason : '+pendingSpaceRes[userkey].reason+'</p></li><li></div> <div class="col-lg-5 text-right"><button type="button" class="btn btn-light btnChangeStatus active approve"><i class="fa fa-check mr-1"></i>Approve</button><button type="button" class="btn btn-danger canceled"><i class="fa fa-times m-1"></i>Canceled</button></li></div></ul></div><hr class="spaceReq-hr" /></div></div>');
                               	 }

                    		});

                    		}
	
                    	}     
                    	else
                        {
                        	console.log("else in");
                        	$(".pendingSpaceMemList").append('<div>No pending requests. <a>Return to venues</a></div>');
                        }
                       }
                  });


              	function changeUserSpaceStatusApprove(userId,spaceId,validateId,status)
              	{
          			$.ajax({
                              type:'POST',
                              url:'spaceRequest.php',
                              data:{'pendingUserID':userId,'pendingSpaceId':spaceId,'pendingValidateId':validateId,'status':status},
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


              	function changeUserVenueStatusCanceled(userId,spaceId,validateId,status)
              	{
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
	                              url:'spaceRequest.php',
	                              data:{'pendingUserID':userId,'pendingSpaceId':spaceId,'pendingValidateId':validateId,'status':status},
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
					
					 var userId = $(this).parents('.getInfoPending').find('.userId').val();
					 var spaceId = $(this).parents('.getInfoPending').find('.spaceId').val();
					 var validateId = $(this).parents('.getInfoPending').find('.validateId').val();


					changeUserSpaceStatusApprove(userId,spaceId,validateId,"approve");	

				
				});

				$(document).on("click", ".canceled", function(){
					
					 var userId = $(this).parents('.getInfoPending').find('.userId').val();
					 var spaceId = $(this).parents('.getInfoPending').find('.spaceId').val();
					 var validateId = $(this).parents('.getInfoPending').find('.validateId').val();

					 
					changeUserVenueStatusCanceled(userId,spaceId,validateId,"canceled");	

				
				});






   			})

   	</script>

    </html>