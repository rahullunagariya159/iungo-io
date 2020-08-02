<?php
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
// Change this to your connection info.
include 'connect.php';
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
$userCount=0;
// subdomain kellogg
  $getHostName = explode('.',  $_SERVER['HTTP_HOST']);  
  // print_r($_SERVER['HTTP_HOST']);
  // die;

  $getSpaceQry = "select * from spaces where name='$getHostName[0]'";
  $getqryExe = mysqli_query($con,$getSpaceQry); 
   $getSpaceRes = mysqli_fetch_assoc($getqryExe);

   $getSpceId = $getSpaceRes['id'];
   $getEmailExtension = $getSpaceRes['emailExtension'];
   $getSpaceName = $getSpaceRes['name'];
   $getSpaceUrl = $getSpaceRes['spaceUrl'];


if(!isset($_POST['sign_user'])){
    // echo "if innn";
    // die;
}
else{
// validate email
    
if(strtolower($getHostName[0]) == strtolower($getSpaceName)) { 
    
    $email = $_POST['email'];
    // $firstname = $_POST['firstname'];
    $user_check_query = "SELECT * FROM accounts WHERE email='$email' and spaceId='$getSpceId' LIMIT 1";
    $result = mysqli_query($con, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    $userCount =  mysqli_num_rows($result);
    

    if ($userCount > 0) { // if user exists
       
        if(substr($user['email'],strpos($user['email'],"@")) != $getEmailExtension) {

            // array_push($errors, "Please use your organizations email");
            $access_check_query = "SELECT access FROM validate WHERE  email='$email' LIMIT 1";
            $result_access = mysqli_query($con, $access_check_query);
            $res_rows = mysqli_num_rows($result_access);
            $user_access = mysqli_fetch_row($result_access);

            // echo "sdfsdfsd" .$res_rows;
            // print_r($user_access);
            // die;   
            // if ($user_access[0]  ) { 
            //     echo "dsfsdfgsdkfh";
            //     // if user has the access
            //     // echo $user_access[0];
            //     // die();
            // }
           
           if($user_access > 0)
           {
                if ($user_access[0] && $user_access[0] != 2) { 
                    
                    // if user has the access
                    // echo $user_access[0];
                    // die();
                }
                else if($user_access[0] == 0)
                {
                    array_push($errors, "Your request is pending for approve by owner of this space");
                }
                else if($user_access[0] == 2)
                {
                 
                    array_push($errors, "You are blocked by the owner of this space");   
                }
                else{
                    
                    array_push($errors, "Please use your organizations email");
                }
            }
            else
            {
                 array_push($errors, "Please enter a proper email and password!");
            }
            
        }
    }
    // else {

        
    //     array_push($errors, "You are not authorized by owner of the space!");
    // }
}


if (count($errors) == 0 && $userCount > 0){
    
    $email = $_POST['email'];
    $user_check_query = "SELECT * FROM accounts WHERE email='$email' LIMIT 1";
    $result = mysqli_query($con, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    $firstname = $user['firstname'];
    $dbFirstname = $user['firstname'];
    $dbuName = $user['name']; 
    $dbUserId = $user['id'];
    $dbPassword = $user['password'];

    // echo $firstname;
    // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
    if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE firstname = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the firstname is a string so we use "s"
        $stmt->bind_param('s', $firstname);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
    }
    if ($stmt->num_rows > 0) {
        // echo "main ifff innnn";
        // die;
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
       
        // echo $_POST['password'];
        // echo $password;
        // die;
        // if (password_verify($_POST['password'], $password)) {

        // $hash = '$2y$10$hZyMd7Iui0XZMesXWUuL/.KLHgVHmHUQDooLcOLgHTsDqGJW8fwb.';
        $passval =  mysqli_real_escape_string($con, $_POST['password']);
        
        // if (password_verify($passval, $hash)) {
        //     echo 'Password is valid!';
        // } else {
        //     echo 'Invalid password.';
        // }
     // echo $dbPassword;
     // die; 
       
        if (password_verify($passval, $dbPassword)) {
            
            // Verification success! User has loggedin!
            // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();


            // echo $dbFirstname;
             // echo $id;
            // echo $getSpceId;;
            // echo $getSpaceUrl;
             //die;

            $_SESSION['loggedin'] = TRUE;
            if($dbFirstname == "")
            {
                 $_SESSION['name'] = $dbuName;
            }
            else
            {
                 $_SESSION['name'] = $dbFirstname;
            }

            //$_SESSION['id'] = $id;
            $_SESSION['id'] = $dbUserId;
            $_SESSION['spaceId'] = $getSpceId;
            $_SESSION['spaceUrl'] =  $getSpaceUrl;

            if($_SESSION['name']=="admin"){
                header('Location: admin.php');
            }
            else{
                header('Location: video-chat/index.php');
            }
        } else {
            array_push($errors,'Incorrect password!');
        }
    } else {
        //array_push($errors,'Incorrect firstname!');
        array_push($errors,'Incorrect email address!');
    }
    $stmt->close();
}else
{
   array_push($errors, "Please enter a proper email and password!");}
}   
?>