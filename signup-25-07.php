<?php 

		include('server.php');


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
	<title>sign in </title>
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
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
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
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/img-01.png" alt="IMG">
				</div>

				<form class="login100-form validate-form" method="post" action="signup.php">
					<?php if(isset($success)) { ?>
							<span style="color: green"><?php echo @$success; ?> </span>
					<?php } ?>
				<?php include('errors.php'); ?>
					<span class="login100-form-title">
						Sign Up
					</span>

                
                    <div class="wrap-input100 validate-input" data-validate = "firstname">
						<input class="input100" type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="First Name">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							 <i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>
                
					<div class="wrap-input100 validate-input" data-validate = "name">
						<input class="input100" type="text" name="name" value="<?php echo $name; ?>" placeholder="Last Name">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							 <i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "About you">
						<input class="input100" type="text" name="aboutyou" value="<?php echo $aboutyou; ?>" placeholder="Short Bio">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							 <i class="fa fa-comment" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "mobile">
						<input class="input100" type="text" name="mobile" value="<?php echo $mobile; ?>" placeholder="mobile">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-mobile" aria-hidden="true"></i>
						</span>
					</div>
				
					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo @$getSpaceEmailExtension ?>">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required" >
						<input class="input100" type="password" name="password_1" placeholder="password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					
					<div class="wrap-input100 validate-input" data-validate = "password">
						
						
						<input class="input100" type="password" name="password_2" placeholder="confirm password" >
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					For security reasons, please use a password you never used anywhere else. It's common sense.

					
					<?php
					if($email)
					{
						if(strtolower($getHostName[0]) == strtolower($getSpaceName) && substr($email,strpos($email,"@")) != $getEmailExtension)
						{
							?>
							     <!-- Reason -->
								<div class="wrap-input100 validate-input" data-validate = "Reason is required">
									<input class="input100" type="text" name="reason" placeholder="Reason" id="reason" required>
									<span class="focus-input100"></span>
									<span class="symbol-input100">
										<i class="fa fa-comment" aria-hidden="true"></i>
									</span>
								</div>

								<div class="container-login100-form-btn">
									<input type="submit" value="Request" name="access" class="login100-form-btn">	
								</div>
							<?php
						}
					}
						else
						{
							?>
							<div class="container-login100-form-btn">
								<button class="login100-form-btn" type="submit" name="reg_user">
									Sign Up
								</button>
							</div>
							<?php
						}

					?>

					<div class="text-center p-t-12">
						<span class="txt1">
							Already Registered ?
						</span>
						<a class="txt2" href="index.php">
							Click here to login
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
	<script src="js/main.js"></script>

</body>
</html>