<?php 
	
		ini_set('session.cookie_domain', '.iungo.io' );
		session_start();
		// include('server.php');
		include 'connect.php';


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


		 // subdomain kellogg
	  @$getUrlHostName = explode('.',  $_SERVER['HTTP_HOST']);  
	  // print_r($_SERVER['HTTP_HOST']);
	  // die;
	 
	  $getSpaceInfoQry = "select * from spaces where name='$getUrlHostName[0]'";
	  $getqryInfoExe = mysqli_query($con,$getSpaceInfoQry); 
	   $getSpaceInfoRes = mysqli_fetch_assoc($getqryInfoExe); 
	   @$getSpaceEmailExtension = @$getSpaceInfoRes['emailExtension'];




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
	
	<link rel="stylesheet" type="text/css" href="css/signup.css">
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
			<div class="signup-titles">
				<div class="signup-form-title">
						Join your organization’s Space
					</div>
					<div class="signup-form-subtitle">
						We need the following information to get you started

					</div>
				</div>
			<div class="wrap-login100">
				
				
				<form class="login100-form validate-form" method="post" action="" id="newUser">
					<?php if(isset($success)) { ?>
							<span style="color: green"><?php echo @$success; ?> </span>
					<?php } ?>
			<!-- 	<?php include('errors.php'); ?> -->


                
                    <div class="wrap-input100 validate-input" data-validate = "firstname">
						<input class="input100 uFname" type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="First Name" required>
						<span class="focus-input100"></span>
						
					</div>
                
					<div class="wrap-input100 validate-input" data-validate = "name">
						<input class="input100 uName" type="text" name="name" value="<?php echo $name; ?>" placeholder="Last Name" required>
						<span class="focus-input100"></span>
						
					</div>

					<!-- <div class="wrap-input100 validate-input" data-validate = "About you">
						<input class="input100" type="text" name="aboutyou" value="<?php echo $aboutyou; ?>" placeholder="Short Bio">
						<span class="focus-input100"></span>
						
					</div>

					<div class="wrap-input100 validate-input" data-validate = "mobile">
						<input class="input100" type="text" name="mobile" value="<?php echo $mobile; ?>" placeholder="mobile">
						<span class="focus-input100"></span>
						
					</div> -->
				
					<div class="wrap-input100 validate-input email-main" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100 emailInpt" type="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo @$getSpaceEmailExtension ?>">
						<div class="email-notice">
							Please use your organization’s email. Otherwise, your account will need to be validated manually by your administrator, which might take some time

						</div>
						<span class="focus-input100"></span>
						
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required" >
						<input class="input100 uPass" type="password" name="password_1" placeholder="password" id="confPass" required>
						<span class="focus-input100"></span>
						
					</div>
					
					
					<div class="wrap-input100 validate-input" data-validate = "password">
						
						
						<input class="input100 uConfPass" type="password" name="password_2"  placeholder="confirm password" required>
						<span class="focus-input100"></span>
						
					</div>
					<!-- <div class="security-notice">For security reasons, please use a password you never used anywhere else. It's common sense.</div>-->
					
					<!-- Reason -->
					<div class="wrap-input100 validate-input inptReason" data-validate = "Reason is required" style="display: none">
						<input class="input100 uReason" type="text" name="reason" placeholder="Why should we allow you access to this Space?" id="reason" required>
						<span class="focus-input100"></span>
						
					</div>

					<div class="container-login100-form-btn btnRegReq" style="display: none">
						<input type="submit" value="Request" name="access" class="login100-form-btn">	
					</div>


					<div class="container-login100-form-btn regUserSub">
						<button class="login100-form-btn" type="submit" name="reg_user">
							Sign Up
						</button>
					</div>

					

					<div class="text-center alreadyReg p-t-12">
						<span class="txt1">
							Already Registered ?
						</span>
						<a class="txt2" href="index.php">
							Sign in instead
						</a>
					</div>

<!--
					<div class="text-center p-t-136">
						<a class="txt2" href="#">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
-->
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
<!--===============================================================================================-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<!--===============================================================================================-->
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
	<script defer src="js/header-menu.js"></script>

	<script>

		
		$(document).ready(function(){

			 

			 //This is used for to check user enter a email address is same as organization email address or not
			$('.emailInpt').keyup(function(){

				var emailIntpVal = "";
				 emailIntpVal = $('.emailInpt').val();
				var getHostName = '<?php echo strtolower($getHostName[0]) ?>';
				var getSpaceName = '<?php echo strtolower($getSpaceName) ?>';
				var getEmailExtension = '<?php echo  $getEmailExtension ?>';
				// console.log(emailIntpVal);

				if(emailIntpVal != "" || emailIntpVal != null)
				{

					var subStrEmail		=	emailIntpVal.substring(emailIntpVal.indexOf("@"), emailIntpVal.length);
					// console.log(getHostName);
					// console.log(getSpaceName);
					// console.log(getEmailExtension);
					// console.log(subStrEmail);
					// console.log("asasd");
					// console.log(emailIntpVal);

					if(getHostName == getSpaceName && subStrEmail != getEmailExtension)
					{
						$('.email-notice').css("display","block");
						$('.inptReason').css("display","block");
						$('.btnRegReq').css("display","block");
						$('.regUserSub').css("display","none");

					}
					else
					{
						$('.email-notice').css("display","none");
						$('.inptReason').css("display","none");
						$('.btnRegReq').css("display","none");
						$('.regUserSub').css("display","block");

					}

					
				}
				else
				{
					$('.email-notice').css("display","none");
					$('.inptReason').css("display","none");
					$('.btnRegReq').css("display","none");
					$('.regUserSub').css("display","block");
				}
					
			});

			$('.emailInpt').change(function(){
				$('.email-notice').css("display","none");
				var emailIntpVal = "";
				 emailIntpVal = $('.emailInpt').val();
				var getHostName = '<?php echo strtolower($getHostName[0]) ?>';
				var getSpaceName = '<?php echo strtolower($getSpaceName) ?>';
				var getEmailExtension = '<?php echo  $getEmailExtension ?>';
				// console.log(emailIntpVal);

				if(emailIntpVal != "" || emailIntpVal != null)
				{

					var subStrEmail = emailIntpVal.substring(emailIntpVal.indexOf("@"), emailIntpVal.length);
					// console.log(getHostName);
					// console.log(getSpaceName);
					// console.log(getEmailExtension);
					// console.log(subStrEmail);
					// console.log("asasd");
					// console.log(emailIntpVal);

					if(getHostName == getSpaceName && subStrEmail != getEmailExtension)
					{
						$('.email-notice').css("display","block");
						$('.inptReason').css("display","block");
						$('.btnRegReq').css("display","block");
						$('.regUserSub').css("display","none");
					}
					else
					{
						$('.email-notice').css("display","none");
						$('.inptReason').css("display","none");
						$('.btnRegReq').css("display","none");
						$('.regUserSub').css("display","block");
					}

					
				}
				else
				{
					$('.email-notice').css("display","none");
					$('.inptReason').css("display","none");
					$('.btnRegReq').css("display","none");
					$('.regUserSub').css("display","block");
				}
					
			});




								


				$("#newUser").validate({
            rules: {                
                firstname: "required",
                name: "required",
                email:{required:true,email:true},
                password_1: "required",
                password_2: {required:true,equalTo:'#confPass'},
                reason:"required",
                // Zip: {required:true,number:true,minlength:5,maxlength:5},
             
            },
            messages: {             
                firstname:  "Please enter firstName",
                name:  "Please enter lastName",
              	email:  "Please enter email",
                password_1:  "Please enter password",
                password_2:  {required : "Please enter confirm password",equalTo:"Please enter the same password as above"},
                reason:"Please write your answer",
                // Zip:  {required:"Please enter zipcode",number:"Please enter Numeric value",minlength:"Please enter only 5 digits",maxlength:"Please enter only 5 digits"},
               
              },
              errorPlacement: function( label, element ) {
                if( element.attr( "name" ) === "sd" || element.attr( "name" ) === "ed"  ) {
                  element.parent().parent().append( label );
                } 
                else
                {
                  label.insertAfter( element );
                }
              },
      		submitHandler: function() {
      			$(".Loader").show();
	          
	               //var form = $('#NewClient').serialize();
	              //  console.log(form);
	              //  return false;

               var form = $('#newUser')[0];

               var data = new FormData(form);
              
	               jQuery.ajax({
	            
	                   type:"post",
	                    data:data,
	                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
	                    processData: false,
	                    url:'signupverify.php',
	                    success: function(dataRes)
		                {
		                 //     console.log("getttt");
		               		 // console.log(dataRes);
		               		$(".Loader").hide();
		               		if(dataRes == "" || dataRes == null)
		               		{	
		               			
		               		}
		               		else
		               		{
			               		var resInfo  = jQuery.parseJSON(dataRes);
			               		// console.log(resInfo);
			               		// console.log(resInfo.errorCode);
			               		if(resInfo.errorCode == "405")
			               		{
			               			
              						swal("Error!", "Something want wrong, please try again!", "info");
              					
			               		}
			               		else if(resInfo.errorCode == "403")
			               		{
			               			swal("Info!", "Email already exists!", "info");
			               		}
			               		else if(resInfo.successCode == "202")
			               		{

			               			var encryptText = JSON.stringify(resInfo.data);
			               			var encryptDone = CryptoJS.AES.encrypt(encryptText,"/");
                					localStorage.setItem('ConfInfoData', encryptDone);
			               			
			               			window.location.replace("signupconfirm.php");

			               			// localStorage.setItem('name', 'asdasdasd');
			               			// localStorage.setItem('password_1', 'asdasdasd');
			               			// localStorage.setItem('password_2', 'asdasdasd');
			               			// localStorage.setItem('reason', 'asdasdasd');
			               			// localStorage.setItem('access', 'asdasdasd');
			               			// localStorage.setItem('reg_user', '');
			               		}
			               		else
			               		{
              						 swal("Error!", "Something want wrong, please try again!", "info");

			               		}

			               		
		               		}


		                }
	                
	                 });

	             

                }           
        });


				$('.login100-form-btn').on('click',function(){
					console.log("cliiccc");
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