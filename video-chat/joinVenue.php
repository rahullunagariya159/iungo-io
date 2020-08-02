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



		//echo "innn";
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Video Chat.">
    <meta name="author" content="Brian Mau">
    <!-- <link href="/css/main.css" rel="stylesheet"> -->
    <link href="./css/joinRequestRoom.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style-new.css">
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
           <div class="custom-scrollbar">
						<?php include_once('sidebar.php'); ?>
			</div>
			 <div class="" id="mylist">
			 	 <a href="javascript:void(0);" class="icon btnIcon">
                    <i class="fa fa-bars"></i>
                  </a>
                  
			 	<div class="card joiningCardStatus" style="width: 18rem;">
				  <!-- <img src="..." class="card-img-top" alt="..."> -->
				  <div class="card-body">
				    <!-- <h5 class="card-title">Your joining request status</h5> -->
				    <p class="card-text">Your request is not approved! please try again latter.</p>
				    <a href="index.php" class="btn btn-primary">Go Back</a>
				  </div>
				</div>
			 </div>
		</div>
	</div>

</body>

 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</html>

