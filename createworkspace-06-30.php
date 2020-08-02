<?php 
      ini_set('session.cookie_domain', '.iungo.io' );
      session_start();
      // ini_set('display_errors', 1);
      // ini_set('display_startup_errors', 1);
      // error_reporting(E_ALL);
      include 'connect.php';
      


      if(isset($_POST['email']))
      {
         
          $email = @$_POST['email'];
          $phone = @$_POST['phone'];
          $pass =  mysqli_real_escape_string($con, $_POST['pass']);
          $workpace = @$_POST['workpace'];
          $createrName =  @$_POST['createrName']; 
          $emailExtension =  @$_POST['emailExtension']; 

          $userFound = 0;    
          $workspaceFound = 0;   
          $user_last_id;   
          $current_date_time = date("Y-m-d h:s a");
          $create_space = 0;
          $newUser;
          // echo $pass;
          // die;

          $password = password_hash($pass, PASSWORD_DEFAULT);


          $venuememeber_last_id;
          $spaceUrl ='https://'.$workpace.'.iungo.io/video-chat/index.php';
            // first check the database to make sure 
            // a user does not already exist with the same  email
            $user_check_query = "SELECT * FROM accounts WHERE email='$email' LIMIT 1";
            $result = mysqli_query($con, $user_check_query);
            $user = mysqli_fetch_assoc($result);
            
            if ($user) { // if user exists

              if ($user['email'] === $email) {
                   $userFound = 1; 
                   $user_last_id = $user['id'];
                   $createrName = $user['name'];
                //array_push($errors, "email already exists");
              }
              else{
                //echo "user not exisrt in elase";
                $userFound = 0; 
              }
            }
            else{

              //echo "user not foundeed";
              $newUser = true;
              $userAddQuery = "INSERT INTO accounts (name,email, mobile,password, spaceName) 
                      VALUES('$createrName','$email','$phone', '$password', '$workpace')";

               
               // mysqli_query($con, $userAddQuery);

                if(mysqli_query($con, $userAddQuery))
                {
                  
                   $user_last_id = mysqli_insert_id($con);



                    $venuemembers_query = "INSERT INTO venuemembers (UserID, VenueID, DateJoined, LastConnection, TotalConnections, ConnectionTime, Role) VALUES ('$user_last_id',1,'$current_date_time','',0, '', 'member')";
  
                  $result_venuemem = mysqli_query($con,$venuemembers_query);
                  $venuememeber_last_id = mysqli_insert_id($con);
                    //$_SESSION['firstname'] = $firstname;
                   $_SESSION['success'] = "You are now logged in";
                   $userFound = 0; 
                }
              
               
               
            }    
            

           $space_check_query = "SELECT * FROM spaces WHERE name='$workpace' LIMIT 1";
            $space_chk_result = mysqli_query($con, $space_check_query);
            $space_res = mysqli_fetch_assoc($space_chk_result); 


            if ($space_res) { // if user exists

              if ($space_res['name'] === $workpace) {
                   $workspaceFound = 1; 
                //array_push($errors, "workpace already exists");
              }
              else{
                
                 $workspaceFound = 0; 
              }
            }
            else{
              //echo "user not foundeed";
               $spaceAddQuery = "INSERT INTO spaces (name, createdTime,ownerId,emailExtension,spaceUrl) 
                      VALUES('$workpace','$current_date_time', '$user_last_id','$emailExtension','$spaceUrl')";

                

                 if(mysqli_query($con, $spaceAddQuery)){
                   
                   $last_created_sapceId =  mysqli_insert_id($con);
                   if($newUser)
                   {

                      $userUpdQuery = "UPDATE accounts SET spaceId='$last_created_sapceId' WHERE id='$user_last_id'";
                     // mysqli_query($con, $userAddQuery);

                      if(mysqli_query($con, $userUpdQuery))
                      {
                        
                          $venuemembers_updQuery = "UPDATE venuemembers SET spaceId='$last_created_sapceId' WHERE id='$venuememeber_last_id' AND UserID='$user_last_id'";
        
                          $result_updVenuemem = mysqli_query($con,$venuemembers_updQuery);

                          //$_SESSION['firstname'] = $firstname;
                         $_SESSION['success'] = "You are now logged in";
                         $userFound = 0; 
                      }
                   }

                  session_regenerate_id();
                   $_SESSION['loggedin'] = TRUE;
                  $_SESSION['spaceId'] = $last_created_sapceId;
                  $_SESSION['id'] =  $user_last_id;
                   $_SESSION['name'] = $createrName;
                    $create_space = 1;
                 }
                //echo "workpace not exisrt in elase";
               $workspaceFound = 0; 
            } 

           // echo $workspaceFound; 
           // echo $userFound; 
           // echo "done";
            echo $create_space;

          die;
      } 
?>



<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="css/workspace.css">
<style>
</style>


<body>
<div class="Loader" style="display: none;"></div>
<form id="regForm" method="post" action="">
  <h1>Create your own space </h1>
  <!-- One "tab" for each step in the form: -->
  <div class="tab">Enter your information:
    <!-- <p><input placeholder="First name..." oninput="this.className = ''" name="fname"></p>
    <p><input placeholder="Last name..." oninput="this.className = ''" name="lname"></p> -->
     <p><input type="text" placeholder="Name..." oninput="this.className = ''" name="name" id="createrName"></p>
     <p><input type="email" placeholder="E-mail..." oninput="this.className = ''" name="email" id="createrEmail"></p>
    <p><input type="number" placeholder="Phone..." oninput="this.className = ''" name="phone" id="createrPhone"></p>
    <p><input placeholder="Password..." oninput="this.className = ''" name="password" type="password" id="createrPass"></p>
    
  </div>
  <div class="tab">Unique space Name:
    <p><input placeholder="enter you space name..." oninput="this.className=''" name="space" id="spaceName" type="text"></p>
    <p><input placeholder="enter a domian extension email like a @kellogg.northwestern.edu " oninput="this.className = ''" name="emailExtension" type="text" id="emailExtension"></p>
    <!-- <p><input placeholder="Phone..." oninput="this.className = ''" name="phone"></p> -->
  </div>
 <!--  <div class="tab">Birthday:
    <p><input placeholder="dd" oninput="this.className = ''" name="dd"></p>
    <p><input placeholder="mm" oninput="this.className = ''" name="nn"></p>
    <p><input placeholder="yyyy" oninput="this.className = ''" name="yyyy"></p>
  </div> -->
  <!-- <div class="tab">Login Info:
    <p><input placeholder="Username..." oninput="this.className = ''" name="uname"></p>
    <p><input placeholder="Password..." oninput="this.className = ''" name="pword" type="password"></p>
  </div> -->
  <div style="overflow:auto;">
    <div style="float:right;">
      <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
    </div>
  </div>
  <!-- Circles which indicates the steps of the form: -->
  <div style="text-align:center;margin-top:40px;">
    <span class="step"></span>
    <span class="step"></span>
    <!-- <span class="step"></span>
    <span class="step"></span> -->
  </div>

   <div style="text-align:center;margin-top:40px;">
    <a href="index.php">Back to login</a>
  </div>
</form>


<!--===============================================================================================-->  
  <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
  <script src="vendor/bootstrap/js/popper.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
        $(document).ready(function(){

            let allDone = false;
            let emailExteValid = false;

             $("#spaceName").focusin(function(){
                var spaceNameValue = $(this).val();
                //console.log(spaceNameValue);
                allDone = false;

            });

            $("#spaceName").focusout(function(){
                      var spaceNameValue = $(this).val();

                      function go(spaceNameValue)
                      {
                          var username    = $.trim(spaceNameValue);
                          
                          var check = function(string) {
                          return string.indexOf(' ') !== -1;
                      };
                          
                          if(check(username) == true)
                          {
                           //alert('space found')
                           return false;
                          }

                      }

                      var chkWhiteSpace = go(spaceNameValue);

                    // console.log( email + phone +workpace); 
                    // console.log(submitBtnTxt);
                    // console.log("call ");
                      if(chkWhiteSpace == false || /^[a-zA-Z0-9-. ]*$/.test(spaceNameValue) == false)
                      {
                        swal("Info!", "Please don't enter any white space and special character in the space name filed!", "info");
                        allDone = false;
                      }
                      else{
                        allDone = true;
                      }
                    //console.log(spaceNameValue);
            });


            $("#emailExtension").focusout(function(){
                  var spaceNameValue = $(this).val();
                  var emailExtVal = spaceNameValue.charAt(0);
                  if(emailExtVal == "@")
                  {
                    emailExteValid = true;  
                  }
                  else
                  {
                    swal("Info!", "Please your email extension is start from '@' sign \n like : @kellogg.northwestern.edu", "info");

                    emailExteValid = false;  
                  }
                  

            });

             $("#emailExtension").focusin(function(){
                var spaceNameValue = $(this).val();

                console.log(spaceNameValue);

                //console.log(spaceNameValue);

                emailExteValid = false;

            });

             
              

              $('#nextBtn').on('click',function(){
                console.log('call');
                var submitBtnVal = $(this).text();

                // console.log(submitBtnVal);
                //  if(submitBtnVal == "Submit")
                //  {
                //      $('#nextBtn').css('display','none');
                //     if(allDone && emailExteValid)
                //     {
                //       $('#nextBtn').css('display','block');
                //     }
                //     else
                //     {
                //       $('#nextBtn').css('display','none');

                //     }
                //  }

                var email =  $('#createrEmail').val();
                var createrName = $('#createrName').val(); 
                var phone = $('#createrPhone').val(); 
                var pass = $('#createrPass').val(); 
                var workpace  = $('#spaceName').val();
                var emailExtension = $('#emailExtension').val();
                var submitBtnTxt = $('#nextBtn').text();

                  if(allDone && emailExteValid)
                  {
                          $.ajax({
                        type:'POST',
                        url:'createworkspace.php',
                        data:{'email':email,'phone' : phone,'pass':pass,'workpace':workpace,'createrName':createrName,'emailExtension':emailExtension},
                        beforeSend: function() {
                            $(".Loader").show();
                            $("#regForm").hide();
                        },
                        success:function(data){
                            //alert(data);
                            console.log(data);

                            $("#regForm").hide();

                            $(".Loader").hide();

                                    if(data == 1)
                                    {
                                        //console.log("iff inn");
                                        var url_generatemsg =  "Your space created successfully!\n Space Url :-  https://"+workpace+".iungo.io/"; 
                                        swal("Success!", url_generatemsg, "success");
                                        setInterval(function(){ 

                                          var redirect_url = "https://"+workpace+".iungo.io/video-chat/index.php"; 


                                            window.location.replace(redirect_url);
                                          // location.reload(true); 

                                        }, 4000);
                                    }
                                    else if(data == 0)
                                    {
                                      swal("Info!", "This space name is already exist!", "info");

                                        console.log("else in");
                                        setInterval(function(){ location.reload(true); }, 2000);
                                    }
                                    else
                                    {
                                        swal("Error!", "Somthing wrong please try again!", "error");

                                        //console.log("else in");
                                        setInterval(function(){ location.reload(true); }, 2000);

                                    }
                             //location.reload(true);
                            }
                            
                         
                    });
                  }
                  else
                  {
                    return;
                  }

                  


              });
              
        })

</script>


<script>
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {

  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:

      // var email =  $('#createrEmail').val();
      // var createrName = $('#createrName').val(); 
      // var phone = $('#createrPhone').val(); 
      // var pass = $('#createrPass').val(); 
      // var workpace  = $('#spaceName').val();
      // var emailExtension = $('#emailExtension').val();

      // var submitBtnTxt = $('#nextBtn').text();
        

        // function go(workpace)
        // {
        //     var username    = $.trim(workpace);
            
        //     var check = function(string) {
        //     return string.indexOf(' ') !== -1;
        // };
            
        //     if(check(username) == true)
        //     {
        //      //alert('space found')
        //      return false;
        //     }

        // }

        // var chkWhiteSpace = go(workpace);

      // console.log( email + phone +workpace); 
      // console.log(submitBtnTxt);
      // console.log("call ");
       //  if(allDone)
       //  {
        
          
       //     $.ajax({
       //              type:'POST',
       //              url:'createworkspace.php',
       //              data:{'email':email,'phone' : phone,'pass':pass,'workpace':workpace,'createrName':createrName,'emailExtension':emailExtension},
       //              beforeSend: function() {
       //                  $(".Loader").show();
       //                  $("#regForm").hide();
       //              },
       //              success:function(data){
       //                  //alert(data);
       //                  console.log(data);
       //                  $("#regForm").hide();

       //                  $(".Loader").hide();

       //                          if(data == 1)
       //                          {
       //                              console.log("iff inn");
       //                              var url_generatemsg =  "Your space created successfully!\n Space Url :-  https://"+workpace+".iungo.io/"; 
       //                              swal("Success!", url_generatemsg, "success");
       //                              setInterval(function(){ 

       //                                var redirect_url = "https://"+workpace+".iungo.io/video-chat/index.php"; 
       //                                  window.location.replace(redirect_url);
       //                                // location.reload(true); 

       //                              }, 4000);
       //                          }
       //                          else if(data == 0)
       //                          {
       //                            swal("Info!", "This space name is already exist!", "info");

       //                              console.log("else in");
       //                              setInterval(function(){ location.reload(true); }, 2000);
       //                          }
       //                          else
       //                          {
       //                              swal("Error!", "Somthing wrong please try again!", "error");

       //                              console.log("else in");
       //                              setInterval(function(){ location.reload(true); }, 2000);

       //                          }
       //                   //location.reload(true);
       //                  }
                        
                     
       //          });

       // }

    //document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>

</body>
</html>
