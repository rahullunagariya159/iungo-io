<?php
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
include '../connect.php';

if(!isset($_SESSION['id']))
{
  header("location:../index.php");
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Video Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

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
    <?php 
      $room_domain=0;
      if(explode('.',  $_SERVER['HTTP_HOST'])[0]!="iungo"){
        $room_domain=1;
      }
?>
        <div class="fluid-container">
            <div class="row mainrow">
                <div class="col-3">
                   
					<?php include_once('sidebar.php'); ?>
                    <div class="rooms">
                        <div class="create-container">
                        <button class="create" onclick="viewform()">Create Venue</button>
                        <div class="addroom" id="addroom">
                            <form action="addroom.php" method="POST">
                                <input type="text" name="roomname" placeholder="Room Name" required>
                                <br>
                                <input type="text" name="roomdesc" placeholder="Room Description" required>
                                <br> 
                                <input type="radio" class="type" name="type" value="Public" required><label for="type">Public</label>
                                <input type="radio" class="type" name="type" value="Secret" required><label for="type">Secret</label>
                                <input type="radio" class="type" name="type" value="Private" required><label for="type">Private</label>
                                <br>
                                <br>
                                <input type="submit" value="Add Room">
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-9" id="mylist">
                    <?php       
    if (isset($_SESSION['loggedin'])) 
      {?>
                        
                        <div class="row search">
                            <div class="col-3">
                                <h3>Venues</h3></div>
                            <div class="col-9">
                                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Venues">
                            </div>
                        </div>

                        <?php 
            $lastlive = strtotime("-5 seconds");
            $querylive= "SELECT COUNT(category), category, `status`
                        FROM accounts
                        WHERE $lastlive<`last-live`   
                        GROUP BY  category,`status`
                        having `status` = 1";
            $resultlive = mysqli_query($con,$querylive);
            
            while($rowlive = mysqli_fetch_row($resultlive)){
                $queryliveuser = "UPDATE category SET liveuser = '".$rowlive[0]."' where Name ='".$rowlive[1]."' ";
                mysqli_query($con,$queryliveuser);
            }; 
            if (mysqli_num_rows($resultlive)==0) { 
                $queryliveuser = "UPDATE category SET liveuser = 0";
                mysqli_query($con,$queryliveuser);
            }
            $query1 = "SELECT * FROM category WHERE Name = 'General'";
            $result = mysqli_query($con, $query1);
            $row=mysqli_fetch_row($result);
			
				$category_id = $row[0];
				$user_id = $_SESSION['id'];
					
				 $check_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id." AND `VenueID` = '".$category_id."' AND `Role` != 'left' ";	
				 $check_venue_member_data = mysqli_query($con, $check_venue_member);


				$check_venue_member_row = mysqli_fetch_row($check_venue_member_data);
        // print_r($check_venue_member_row);
				 if($check_venue_member_row){ 
					$already_joined = 'yes';
					$submit_value = 'Leave';
					$title = '<span class="custom_again_join">'.$row[1].'</span>';
				 }else{
					 $already_joined = '';
					 $submit_value = 'Join';
					 $title = $row[1];
				 }
				 
        ?>
                            <br>
                            <br>
                            <form action="/video-chat/chatroom.php" method="post">
                                <input type="text" name="createroom" value="<?php echo $row[1]; ?>" hidden>
                                <input type="text" name="createroom_id" value="<?php echo $row[0] ?>" hidden>
                                <input type="text" name="already_joined" class="already_joined" value="<?php echo $already_joined; ?>" hidden>
                                <input type="text" name="room_domain" value="<?php echo $room_domain ?>" hidden>
                                <h2><?php echo $title; ?> <span class="user-number">Members[<?php echo $row[3] ?>]</span><span class="user-number"> Live[<?php echo $row[4] ?>]</span> <span class="user-number"><input type="submit" value="<?php echo $submit_value; ?>" ></span> </h2>
                                <?php echo $row[2] ?>
                            </form>

        <?php
            // $query1 = "SELECT * FROM category WHERE Name != 'General' AND type !='Private' ORDER BY liveuser DESC";
            $query1 = "SELECT * FROM category WHERE Name != 'General'  ORDER BY liveuser DESC";
            $result = mysqli_query($con, $query1);  
            while($row=mysqli_fetch_row($result))
            {
              
      				$category_id = $row[0];
      				$user_id = $_SESSION['id'];
              @$venueType = $row[7];
              //print_r($row);
              //echo $venueType; 
      					
                // $check_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id." AND `VenueID` = '".$category_id."' AND `Role` != 'left' ";  
      				  $check_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id." AND `VenueID` = '".$category_id."' AND `Role` != 'left' AND `Role` != 'pending' ";	

                

      				 $check_venue_member_data = mysqli_query($con, $check_venue_member);
      				$check_venue_member_row = mysqli_fetch_row($check_venue_member_data);


               $checkPending_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id." AND `VenueID` = '".$category_id."'  AND `Role` = 'pending' "; 

               $checkPending_venue_member_data = mysqli_query($con, $checkPending_venue_member);
              $checkPending_venue_member_row = mysqli_fetch_row($checkPending_venue_member_data);



    				 if($check_venue_member_row){
               //echo "leave"; 
      					$already_joined = 'yes';
      					$submit_value = 'Leave';
      					$title = '<span class="custom_again_join">'.$row[1].'</span>';
      				 }else if($checkPending_venue_member_row){
                 
                  //echo "pending";
                $title = $row[1];

               }else{
               // echo "join";
      					 $already_joined = '';
      					 $submit_value = 'Join';
      					 $title = $row[1];
    				 }
        ?>
                            
                            

                            <?php if($row[7]=="Public"){
                             ?>
                            <form action="https://iungo.io/video-chat/chatroom.php" method="post">
                        <?php } else{

                                $query = "SELECT id FROM accounts WHERE id = '".$_SESSION['id']."' ";
                                $resultid = mysqli_query($con,$query);
                                $id = mysqli_fetch_row($resultid)[0];

                                $query = "SELECT * FROM category WHERE Name ='$row[1]' LIMIT 1";
                                $resultmem = mysqli_query($con,$query);
                                $rowmem = mysqli_fetch_array($resultmem);
                                // $members = $rowmem['members'];
                                $members = $rowmem['members'];
                                $moderate = $rowmem['moderator'];
                                $members = explode(",", $members);
                                $getModerator = explode(",", $moderate);
                                //print_r($members);

                                if (in_array($id,$members, TRUE) || in_array($id,$getModerator, TRUE))
                                { ?>
                                    <form action="https://iungo.io/video-chat/chatroom.php" method="post">
                        <?php   } 
                                else{



                            ?>
                            <form action="https://iungo.io/video-chat/privateVenueJoinRequest.php" method="post">
                        <?php }}?>

                                <input type="text" name="createroom" value="<?php echo $row[1] ?>" hidden>
                                <input type="text" name="createroom_id" value="<?php echo $row[0] ?>" hidden>
                                <input type="text" name="room_domain" value="<?php echo $room_domain ?>" hidden>
                                <input type="text" name="already_joined" class="already_joined" value="<?php echo $already_joined; ?>" hidden>
                                <h2><?php echo $title; ?> <span class="user-number">Members[<?php echo $row[3] ?>]</span><span class="user-number"> Live[<?php echo $row[4] ?>]</span> 
                                  <span class="user-number">
                                    <?php if($checkPending_venue_member_row)
                                    { ?>
                                      <input type="button" value="pending approval" name="pending" class="pendingBtn" />  
                                    <?php }else {?>
                                      <input type="submit" value="<?php echo $submit_value; ?>" name="join">
                                    <?php }?>
                                </span> </h2>
                                <?php echo $row[2] ?>
                            </form>

        <?php
             }   }
        ?>
				
                </div>
            </div>
        </div>
        <a style="display:none" href="https://ritikkansal.com">Made this website with love by Ritik Kannsal</a>

        <!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> -->
        <!-- 
            <script
      src="https://code.jquery.com/jquery-3.4.1.min.js"
      integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
      crossorigin="anonymous"></script> -->

        <!-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script> -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        
        <script>
            function myFunction() {
              // Declare variables
              var input, filter, ul, li, a, i, txtValue;
              input = document.getElementById('myInput');
              filter = input.value.toUpperCase();
              ul = document.getElementById("mylist");
              li = ul.getElementsByTagName('form');

              // Loop through all list items, and hide those who don't match the search query
              for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("h2")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                  li[i].style.display = "";
                } else {
                  li[i].style.display = "none";
                }
              }
            }
        </script>
        <script>
            function viewform(){
                    var element = document.getElementById("addroom");
                    element.classList.toggle("addroomview");
            }
        </script>
		<script>
   //          $(document).ready(function(){


   //            // var firstName = $('#first_name').val();
   //            // var lastName = $('#last_name').val();
   //            // var intials = $('#first_name').val().charAt(0) + $('#last_name').val().charAt(0);
   //            // console.log(intials);
   //            // var profileImage = $('.defaultImgDisplay').text(intials);
   //            // console.log(profileImage);
   //            // $(document).on('hidden.bs.modal', '.modal', function () {
   //            //     $('.modal:visible').length && $(document.body).addClass('modal-open');
   //            // });


			// 	$(".custom_again_join").click(function(){
			// 	    $(this).parent().parent().find(".already_joined").val('no');
			// 	     $(this).parent().parent().submit();  
			// 	});


   //        $('.lblChangePass').on('click',function(){
             

   //            $('#changePassModal').modal('show');

   //        });



   //        $('.changePassModalClose').on('click',function(){
             
   //            $('#changePassModal').modal('toggle');

   //        });

   //        $('.changePass').on('click',function(){
             
            
   //          var oldPass = $('#oldPass').val();
   //          var newPass = $('#newPass').val();
   //          var confPass = $('#confPass').val();
   //          var uid = $('#uid').val();
            
            
   //          if(oldPass == "")
   //          {
   //            swal("Info!", "Please enter old password!", "info");

   //          }
   //          else if(newPass == "")
   //          {
   //            swal("Info!", "Please enter new password!", "info");

   //          }
   //          else if(confPass == "")
   //          {
   //            swal("Info!", "Please enter confirm password!", "info");

   //          }
   //          else if(newPass != confPass)
   //          {
   //            swal("Error!", "New password and Confirm password are not match!", "error");

   //          }
   //          else
   //          {
                
   //              swal({
   //                      title: "Are you sure?",
   //                      text: "Once Changed, you will not be able to recover this old password!",
   //                      icon: "warning",
   //                      buttons: true,
   //                      dangerMode: true,
   //                    })
   //                    .then((willDelete) => {
   //                      if (willDelete) {

   //                        $.ajax({
   //                                type:'POST',
   //                                url:'sidebar.php',
   //                                data:{'newPass':newPass,'oldPass':oldPass,'uId' : uid},
   //                                success:function(data){
   //                                    //alert(data);
   //                                    //console.log(data);
   //                                            if(data == 1)
   //                                            {
                                                  
   //                                                swal("Success!", "Your password changed successfully!", "success");
   //                                                $('#frmPassReset').trigger("reset");
   //                                                $('#changePassModal').modal('toggle');
   //                                                // setInterval(function(){ 

                                                     

   //                                                //  }, 2000);
   //                                            }
   //                                            else if(data == 2)
   //                                            {

   //                                               swal("Error!", "Your old password is incorrect!", "error");

                                                
                                                  
   //                                            }
   //                                            else
   //                                            {
                                                
   //                                                swal("Error!", "Somthing wrong please try again!", "error");
   //                                                $('#frmPassReset').trigger("reset");

                                               
   //                                                // setInterval(function(){ 

   //                                                //  }, 2000);

   //                                                $('#changePassModal').modal('toggle');

   //                                                //setInterval(function(){ location.reload(true); }, 3000);
   //                                            }
   //                                     //location.reload(true);
   //                                    }
                                      
                                   
   //                            });
                         
   //                      } else {
   //                        swal("Your current password is safe!");
   //                      }
   //                    });
                
   //          }

   //            //$('#changePassModal').modal('toggle');

   //        });




          
           
   //                     $('#removeImage').on('click',function(){
   //                      //alert("asdasd");
   //                          var imageName =  '<?php echo $profilePicture; ?>';
   //                          var uSession = '<?php echo $_SESSION['id']; ?>';

   //                          console.log(imageName);
   //                          console.log(uSession);


   //                          swal({
   //                            title: "Are you sure?",
   //                            text: "Once deleted, you will not be able to recover this imaginary file!",
   //                            icon: "warning",
   //                            buttons: true,
   //                            dangerMode: true,
   //                          })
   //                          .then((willDelete) => {
   //                            if (willDelete) {

   //                              $.ajax({
   //                                      type:'POST',
   //                                      url:'sidebar.php',
   //                                      data:{'removeImage':imageName,'uSession' : uSession},
   //                                      success:function(data){
   //                                          //alert(data);
   //                                          console.log(data);
   //                                                  if(data == 1)
   //                                                  {
   //                                                      console.log("iff inn");
   //                                                      swal("Success!", "Your profile image removed successfully!", "success");
   //                                                      setInterval(function(){ location.reload(true); }, 3000);
   //                                                  }
   //                                                  else
   //                                                  {
   //                                                      swal("Error!", "Somthing wrong please try again!", "error");

   //                                                      console.log("else in");
   //                                                      setInterval(function(){ location.reload(true); }, 3000);

   //                                                  }
   //                                           //location.reload(true);
   //                                          }
                                            
                                         
   //                                  });
                               
   //                            } else {
   //                              swal("Your profile image is safe!");
   //                            }
   //                          });
                                                        
   //                           // $.ajax({
   //                           //            type:'POST',
   //                           //            url:'sidebar.php',
   //                           //            data:{'removeImage':imageName,'uSession' : uSession},
   //                           //            success:function(data){
   //                           //                //alert(data);
   //                           //                console.log(data);
   //                           //                        if(data == 1)
   //                           //                        {
   //                           //                            console.log("iff inn");
   //                           //                            swal("Success!", "Your profile image removed successfully!", "success");
   //                           //                            setInterval(function(){ location.reload(true); }, 3000);
   //                           //                        }
   //                           //                        else
   //                           //                        {
   //                           //                            swal("Error!", "Somthing wrong please try again!", "error");

   //                           //                            console.log("else in");
   //                           //                            setInterval(function(){ location.reload(true); }, 3000);

   //                           //                        }
   //                           //                 //location.reload(true);
   //                           //                }
                                            
                                         
   //                           //        });

   //                  });
               
			// });
        </script>

</body>

</html>