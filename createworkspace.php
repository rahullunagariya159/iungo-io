<?php include('authenticate.php') ?>
<?php //include('validate.php') ?>
<?php
 if(isset($_POST['action']) && $_POST['action'] == 'check_email_exist'){
	$email = $_REQUEST['email'];
	if(isset($email) && !empty($email)){
		$email_check = "SELECT id FROM accounts WHERE email='".$email."' ";
		$resultemail = mysqli_query($con,$email_check);
		$rowmembers = mysqli_fetch_row($resultemail);
		
		if(isset($rowmembers) && !empty($rowmembers)){
			echo "0";
		}else{
			echo "1";
		}
	}
	exit;
}
 ?>
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
      <link rel="stylesheet" type="text/css" href="css/create-work-space.css">
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
                  Create a Space for your organization
               </div>
               <!-- <div class="signup-form-subtitle">
                  Provide your email and password to log in
                  </div> -->
            </div>
            <div class="wrap-login100">
               <form class="login100-form validate-form" method="post" >
                  <?php include('errors.php'); ?>
                  <!-- multistep form -->
                  <!-- progressbar -->
                  <!-- <ul id="progressbar">
                     <li class="active">Account Setup</li>
                     <li>Social Profiles</li>
                     <li>Personal Details</li>
                     <li>test</li>
                     </ul> -->
                  <!-- fieldsets -->
                  <fieldset>
                     <h3 class="fs-subtitle">Step 1: What is your email adderess</h3>
                     <input type="text" class="email" id="email" name="email" placeholder="Email" />
					 <div class="email_error"></div>
                     <!-- <input type="button"  value="Next" /> -->
                     <button name="next" class="action-button btnEmail">Next</button>
                  </fieldset>
                  <fieldset>
                     <!-- <h2 class="fs-title">Create your account</h2> -->
                     <h3 class="fs-subtitle">Step 2: email verification – What is the code you received via email?</h3>
                     <input type="text" name="code" placeholder="code" class="emailCode"/>
                     <!-- <input type="button" name="previous" class="previous action-button" value="Previous" /> -->
                     <button name="previous" class="previous action-button" >Previous</button>
                     <!-- <input type="button" name="next" class="action-button fBtn" value="Next" /> -->
                     <button name="next" class="action-button fBtn" >Next</button>
                  </fieldset>
                  <fieldset>
                     <!-- <h2 class="fs-title">Social Profiles</h2> -->
                     <h3 class="fs-subtitle">Step 3: create a unique URL for your space</h3>
                     <input type="text" name="orgName" placeholder="Organization name" />
                     <input type="text" name="orgSpaceurl" placeholder="Space url" />
                     <!-- <input type="button" name="previous" class="previous action-button" value="Previous" /> -->
                     <button name="previous" class="previous action-button" >Previous</button>
                     <!-- <input type="button" name="next" class="next action-button" value="Next" /> -->
                     <button name="next" class="next action-button" >Next</button>
                  </fieldset>
                  <fieldset>
                     <!-- <h2 class="fs-title">Personal Details</h2> -->
                     <h3 class="fs-subtitle">Step 4: set your password</h3>
                     <input type="text" name="password" placeholder="Password" class="password" />
                     <input type="button" name="previous" class="previous action-button" value="Previous" />
                     <!-- <input type="button" name="next" class="next action-button" value="Next" /> -->
                     <button name="next" class="next action-button" >Next</button>
                  </fieldset>
                  <fieldset>
                     <!-- <h2 class="fs-title">Personal Details</h2> -->
                     <h3 class="fs-subtitle">Final step: set an email extension for your Space</h3>
                     <h5 class="fs-subtitle">Improve security: Accounts created from emails with this extension will be automatically approved. You will still have to manually approve other emails. Leave empty to manually approve all accounts</h5>
                     <input type="text" name="orgEmail" placeholder="@YourOrganization.xy" class="orgEmail" />
                     <!-- <input type="button" name="previous" class="previous action-button" value="Previous" /> -->
                     <button name="previous" class="previous action-button" >Previous</button>
                     <!-- <input type="submit" name="submit" class="submit action-button" value="Submit" /> -->
                     <button name="next" class="submit action-button" >Submit</button>
                  </fieldset>
                  <div class="text-center alreadyReg p-t-12">
                     <span class="txt1">
                     Already have a Space? 
                     </span>
                     <a class="txt2" href="index.php">
                     Sign in instead
                     </a>
                  </div>
                  <!--
                     <div class="text-center p-t-136">
                     	
                     </div>
                     -->
               </form>
               <div class="signup-right-img">
                  <img src="images/create-work-space.svg" alt="IMG">
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
      <!--===============================================================================================-->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script>
$(document).ready(function () {

	//jQuery time
	var current_fs, next_fs, previous_fs; //fieldsets
	var left, opacity, scale; //fieldset properties which we will animate
	var animating; //flag to prevent quick multi-click glitches


	function perfomNextClickEvent(currBtnVal) {
		if (animating) return false;
		animating = true;

		current_fs = $(currBtnVal).parent();
		next_fs = $(currBtnVal).parent().next();

		//activate next step on progressbar using the index of next_fs
		// $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

		//show the next fieldset
		next_fs.show();
		//hide the current fieldset with style
		 current_fs.animate({
			opacity: 0
		}, {
			step: function (now, mx) {
				//as the opacity of current_fs reduces to 0 - stored in "now"
				//1. scale current_fs down to 80%
				scale = 1 - (1 - now) * 0.2;
				//2. bring next_fs from the right(50%)
				left = (now * 50) + "%";
				//3. increase opacity of next_fs to 1 as it moves in
				opacity = 1 - now;
				current_fs.css({
					'transform': 'scale(' + scale + ')',
					'position': 'absolute'
				});
				next_fs.css({
					'left': left,
					'opacity': opacity
				});
			},
			duration: 800,
			complete: function () {
				current_fs.hide();
				animating = false;
			},
			//this comes from the custom easing plugin
			easing: 'easeInOutBack'
		}); 
	}


	$(".next").click(function () {
		var currBtn = this;
		perfomNextClickEvent(currBtn);
		return false;		
	});

	$(".fBtn").click(function () {
		var currBtn = this;
		perfomNextClickEvent(currBtn);
		return false;
	});

	$(".btnEmail").click(function () {
		$(".email_error").html('');
		var email = $("#email").val();
		var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
		var currBtn = this;

        if(email == '' || email == null){
			$(".email_error").html("Please Enter Email First.");
			return false;
		}else if(!pattern.test(email)){
			$(".email_error").html("Please Enter Valid Email Address.");
			return false;
		}else {
			
			  $.ajax({
						 type:'POST',
						  url:'createworkspace.php',
					   data:{'email':email,'action':'check_email_exist'},
						success:function(data){
							if(data == 0){
								$(".email_error").html("This Email is already registered with us, so please login into website.");
								return false;
							}else{			
							perfomNextClickEvent(currBtn);
							return false;
							}
                              }
                        });
				return false;	
		}
	});


	$(".previous").click(function () {
		if (animating) return false;
		animating = true;

		current_fs = $(this).parent();
		previous_fs = $(this).parent().prev();

		//de-activate current step on progressbar
		// $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

		//show the previous fieldset
		previous_fs.show();
		//hide the current fieldset with style
		current_fs.animate({
			opacity: 0
		}, {
			step: function (now, mx) {
				//as the opacity of current_fs reduces to 0 - stored in "now"
				//1. scale previous_fs from 80% to 100%
				scale = 0.8 + (1 - now) * 0.2;
				//2. take current_fs to the right(50%) - from 0%
				left = ((1 - now) * 50) + "%";
				//3. increase opacity of previous_fs to 1 as it moves in
				opacity = 1 - now;
				current_fs.css({
					'left': left
				});
				previous_fs.css({
					'transform': 'scale(' + scale + ')',
					'opacity': opacity
				});
			},
			duration: 800,
			complete: function () {
				current_fs.hide();
				animating = false;
			},
			//this comes from the custom easing plugin
			easing: 'easeInOutBack'
		});
		return false;
	});

	$(".submit").click(function () {
		return false;
	})


});
      </script>
   </body>
</html>