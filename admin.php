<?php
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin</title>
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
</head>
<body>
	<style>
		.wrap-login100{
			padding-top: 10px;
		}
		.row{
			width: 100%;
			margin-bottom: 20px;
		}
		.right{
			text-align: right;
		}
		.center{
			text-align: center;
		}
		.requests-box{
			border: solid 1px black;
		}
		.col-3 .row{
			margin-bottom: 1px;
		}
	</style>
	<?php
	//if ($_SESSION['name'] == "admin" ){ ?>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="row">
					<div class="col-3">IUNGO.IO</div>
					<div class="col-3"></div>
					<div class="col-3 right">ADMIN</div>
					<div class="col-3 right"><a href="logout.php">logout</a></div>
				</div>
				<div class="row center">
					<?php
	if ($_SESSION['name'] == "admin" ){ ?>
					<div class="col">Validation Requests of Kellogg</div>
			<?php	}?>
					<?php
	if ($_SESSION['name'] == "admin" ){ ?>
					<div class="col">Validation Requests of Chatrooms</div>
			<?php	}?>
				</div>
				
				<div class="row requests-box">
					<?php include('requests.php') ?>
				</div>

			</div>
		</div>
	</div>
	<?php
//}
?>
	
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->

<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>