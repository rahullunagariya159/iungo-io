<?php 
	
	// use PHPMailer\PHPMailer\PHPMailer;
	// use PHPMailer\PHPMailer\Exception;
	//  include_once('PHPMailer.php');	
	 require_once('phpmailer/PHPMailerAutoload.php');
	//  include_once('src/Exception.php');
	// // require './src/PHPMailer.php';
	//  include_once('src/SMTP.php');	

	 // require 'src/Exception.php';
	 
	 // require 'src/SMTP.php';

// 'username' => 'test.dds0001@gmail.com',
// 	  'password' => 'daydreamsoft1234',

	 // $mail->Username = "test1001.dds@gmail.com";
	 //    $mail->Password = "Test@001";
	
function iungo2($secrateCode,$sendTo){

		$getCode = $secrateCode;

		$emailBody = "<div style='justify-content:center;text-align:center'><h1 style='margin:0px'><b>Passcode</b></h1><span style='font-size:27px'>".$getCode."</span><h5 style='margin:0px'>Above is your passcode for unlock dialer vault</h5></div><br/><div style='justify-content:left;text-align:left'><span>Thanks,<span><br /><span>SundayLab Team</span></div>";
		
		$mail = new PHPMailer(true);	
		
		$mail->IsSMTP(); // enable SMTP

	    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
	    $mail->SMTPAuth = true; // authentication enabled
	    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	    $mail->Host = "smtp.gmail.com";
	    $mail->Port = 465; // or 587 or 465
	    $mail->IsHTML(true);
	    $mail->Username = "test1001.dds@gmail.com";
	    $mail->Password = "Test@001";
	    $mail->SetFrom("test1001.dds@gmail.com","Test@001");
	    $mail->Subject = "Dialer Vault Passcode";
	    $mail->Body = $emailBody;
	    
	    //test1002.dds@gmail.com
	    $mail->AddAddress($sendTo);
		//$mail->send();
		if(!$mail->send()) {
		  echo 'Message was not sent.';
		  echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
		  echo 'Message has been sent.';
		}

		 echo json_encode($getCode);
}


function iungo($secrateCode,$sendTo){

		$getCode = $secrateCode;

		$emailBody = "<div style='justify-content:center;text-align:center'><h1 style='margin:0px'><b>verification code</b></h1><span style='font-size:27px'>".$getCode."</span><h5 style='margin:0px'>Above is your verification code for verify your email address</h5></div><br/><div style='justify-content:left;text-align:left'><span>Thanks,<span><br /><span>Iungo Team</span></div>";
		

		$mail = new PHPMailer(true);

            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            // $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 2;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = 'iungo.io';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            //Set the SMTP port number - likely to be 25, 465 or 587
            // $mail->Port = 587;
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;

            //Username to use for SMTP authentication
            // $mail->Username = 'test1001.dds@gmail.com';
            $mail->Username = 'no-reply@iungo.io';
            //Password to use for SMTP authentication
            // $mail->Password = 'Test@001';
            $mail->Password = ']%<61c1d@qis';
            //Set who the message is to be sent from
             $mail->setFrom('no-reply@iungo.io', 'IUNGO');
            //Set who the message is to be sent to
            $mail->addAddress($sendTo);
            //Set the subject line
            $mail->Subject = "Verification Code";

            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($emailBody);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
          //  $mail->AddAttachment('https://mysunless.com/assets/userimage/20180913115040.jpg', '20180913115040.jpg');
            //send the message, check for errors
            if (!$mail->send()) {
            	
              return false;
            } else {
            	
               return true;
            }
}


// if(isset($_POST['appName']))
// {
// 	$appName = $_POST['appName'];

// 	if($appName == "iungo")
// 	{
// 		iungo();
// 	}
	
// }
// else
// {
// 	echo json_encode("Please provide proper information");
// 	die;
// }





?>