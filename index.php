<?php include('authenticate.php') ?>
<?php //include('validate.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V1</title>
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
	<!-- <link rel="stylesheet" type="text/css" href="css/main.css"> -->
	<link rel="stylesheet" type="text/css" href="css/signin.css">
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

	<?php include_once('header.php'); ?>

	<div class="limiter">
		<div class="container-login100">

			<div class="signup-titles">
				<div class="signup-form-title">
						Join your organization’s Space
					</div>
					<div class="signup-form-subtitle">
						Provide your email and password to log in
					</div>
				</div>
			<div class="wrap-login100">

				

				<form class="login100-form validate-form" action="index.php" method="post">

					
				
				<?php include('errors.php'); ?>
					

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="email" name="email" placeholder="email" id="username"  value="<?php echo $_POST['email']; ?>" required>
						<span class="focus-input100"></span>
						
					</div>
					
					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="password" placeholder="Password" id="password" required>
						<span class="focus-input100"></span>
						
					</div>

					<div class="container-login100-form-btn">
						<input type="submit" value="Login" name="sign_user" class="login100-form-btn">	
					</div>


					<div class="text-center alreadyReg p-t-12">
						<span class="txt1">
							Not yet registered? 
						</span>
						<a class="txt2" href="signup.php">
							Create an account
						</a>
					</div>
			
<!--
					<div class="text-center p-t-136">
						
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
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
	<script defer src="js/header-menu.js"></script>

</body>
</html>