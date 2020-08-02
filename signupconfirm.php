<?php 

		include('server.php');

	    session_start();
	    
	    $signConfEmailID = "";
	    $verifyEmailToken = "";

		if(isset($_SESSION['signConfEmail']))
		{
			$signConfEmailID = $_SESSION['signConfEmail'];
			$verifyEmailToken = $_SESSION['verifyEmailToken'];
			

		}
		else
		{
		  header('Location: ../index.php');
		}
	
	  @$getUrlHostName = explode('.',  $_SERVER['HTTP_HOST']);  
	  // print_r($_SERVER['HTTP_HOST']);
	  // die;
	 
	  $getSpaceInfoQry = "select * from spaces where name='$getUrlHostName[0]'";
	  $getqryInfoExe = mysqli_query($con,$getSpaceInfoQry); 
	   $getSpaceInfoRes = mysqli_fetch_assoc($getqryInfoExe); 
	   @$getSpaceEmailExtension = @$getSpaceInfoRes['emailExtension'];


	   if(isset($_POST['emailVerifCode']))
	   {	

	   		$getInputVerifCode = $_POST['emailVerifCode']; 
	  
	   		
	   		if($verifyEmailToken == $getInputVerifCode)
	   		{
	   			echo "true";die;
	   		}
	   		else
	   		{
	   			echo "false";die;
	   		}
	   		
	   }


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>sign up </title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<!-- <link rel="stylesheet" type="text/css" href="css/util.css"> -->
	
	<link rel="stylesheet" type="text/css" href="css/signup-confirm.css">
	<!-- <link rel="stylesheet" type="text/css" href="css/header.css"> -->
<!--===============================================================================================-->

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
		
	<!-- <div class="header">
		<div class="logo-text">
			iungo
		</div>
		<div class="header-menu" id="myLinks">
			<ul>
				<li><a>Why iungo?</a></li>
				<li><a>How it works </a></li>
				<li><a>Join a Space</a></li>
				<li class="create-space-tab"><a>Create a Space</a></li>

			</ul>
		</div>	

		<div class="mobile-header" onclick="myFunction(this)">
		  <div class="bar1"></div>
		  <div class="bar2"></div>
		  <div class="bar3"></div>
		</div>

		 <a href="javascript:void(0);" class="icon" onclick="myFunction()">
		    <i class="fa fa-bars"></i>
		</a> 

	</div> -->

		<?php include_once('header.php'); ?>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				
				
				<form class="login100-form validate-form" method="post" action="" onSubmit="return false;">
			
					<div class="signup-titles">
						<div class="signup-form-title">
							Join your organization’s Space
						</div>
						<div class="signup-form-subtitle">
							Thank you! Before we redirect you to your Space, we need to verify your email address. 
						</div>
						<div class="email-verify-title">
							What is the code you received via email?

						</div>
					</div>

                
                    <div class="wrap-input100 validate-input" data-validate = "verifycode">
						<input class="input100 inptVerifyCode" type="text" name="verifycode" value="" placeholder="Code">
						<span class="focus-input100"></span>
						
					</div>
                
					<div class="container-login100-form-btn">
						<button class="login100-form-btn btnEmailVerify" type="button" name="emailConfirmValid">
							Go to your Space
						</button>
					</div>
					

				</form>

				<div class="signup-right-img">
					<img src="images/signup_page_img.svg" alt="IMG">
				</div>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<!-- <script src="js/main.js"></script> -->
	<script defer src="js/header-menu.js"></script>

	 <script>

	
		$(document).ready(function(){
			var verifi = '<?php echo $_SESSION['verifyEmailToken'] ?>';
				// console.log(verifi);
			$('.btnEmailVerify').on('click',function(event){

				event.preventDefault();
				

				var getEmailCode = $('.inptVerifyCode').val().trim();
					$(".Loader").show();
					$.ajax({
                              type:'POST',
                              url:'signupconfirm.php',
                              data:{'emailVerifCode':getEmailCode},
                              success:function(data){
                                  //alert(data);
                                 
                                  console.log(data);
                                           if(data == "true")
                                          {	

                                          	  var getUserInfo = localStorage.getItem('ConfInfoData');
										
											  var descryptdone = CryptoJS.AES.decrypt(getUserInfo,"/").toString(CryptoJS.enc.Utf8);
											  //console.log(descryptdone);
											    var parseData = JSON.parse(descryptdone);
											    	console.log(parseData);

											    	// This ajax is used for  insert new user the entry in database.
											    $.ajax({
						                              type:'POST',
						                              url:'signupdone.php',
						                              data:parseData,
						                              success:function(resInfo){
						                              	 $(".Loader").hide();
						                              	var resultInfo  = jQuery.parseJSON(resInfo);
						                              	console.log(resultInfo);

						                              	if(resultInfo.successCode == "204")
						                              	{
						                              		swal("Success","Your request send successfully to space administrator","success");

						                              		setInterval(function(){ 
			               										window.location.replace("index.php");

						                              		 }, 3000);
						                              	}
						                              	else if(resultInfo.successCode == "203")
						                              	{
						                              		swal("Success","Your email verify successfully","success");

						                              		setInterval(function(){  
			               										window.location.replace("index.php");

						                              		}, 3000);
						                              	}

						                              }
						                          });

                                          }
                                          else
                                          {
						                      $(".Loader").hide();

						                     swal("Error!","please enter a valid code","error");
                                          	
                                          }
                                        
                                  }
                                  
                               
                              });

			});
			
		});

	</script> 


		<!-- <script>
		function myFunction() {
		  var x = document.getElementById("myLinks");
		  if (x.style.display === "block") {
		    x.style.display = "none";
		  } else {
		    x.style.display = "block";
		  }
		}
		</script> -->
</body>
</html>