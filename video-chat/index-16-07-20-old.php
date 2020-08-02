<?php
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
include '../connect.php';
include 'videoChatConstant.php';
  @$spaceId = @$_SESSION['spaceId'];

 

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <link rel="stylesheet" href="css/style-new.css">
    <!-- <link rel="stylesheet" href="css/sidebar-old.css"> -->

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

                <div class=" col-sm-4 col-xl-3 custom-scrollbar" id="myLinks">
                   
					<?php include_once('sidebar-21-07.php'); ?>
                    <!-- <div class="rooms">
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
                    </div> -->


                </div>

                <div class="col-sm-8 col-12 col-xl-9" id="mylist">

                  <a href="javascript:void(0);" class="icon btnIcon">
                    <i class="fa fa-bars"></i>
                  </a>
                    
                  <?php       
    if (isset($_SESSION['loggedin'])) 
      {?>
                  <div class="main_container">
                      <div class="team_header d-flex align-items-center justify-content-between">
                        <div class="join font-weight-bold">Join or Create a venue</div>
                        <div class="search_button_header d-flex align-items-center">
                          <div class="search_sec position-relative">
                           
                            <input type="text" id="myInput" onkeyup="myFunction()"  placeholder="Search Venues">
                            <a href="#"><img src="images/search.png"></a>
                          </div>
                          <button class="create_team_button position-relative" >Create a New Venue<img src="images/team.png"></button>
                        </div>
                      </div>
                      <div class="main_sec_containers">

                         <?php 
                          $lastlive = strtotime("-5 seconds");
                          $querylive= "SELECT COUNT(category), category, `status`
                                      FROM accounts
                                      WHERE spaceId='$spaceId' and $lastlive<`last-live`   
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
                          // $query1 = "SELECT * FROM category WHERE Name = 'General'";
                          $query1 = "SELECT * FROM category WHERE Name = 'General' AND spaceId='".$spaceId."'";
                          $result = mysqli_query($con, $query1);
                          $row=mysqli_fetch_row($result);
                         // print_r($row);
                      $category_id = $row[0];
                      $general_room_type = $row[7]; 
                     
                      $general_venue_img = $row[9];
                      $user_id = $_SESSION['id'];
                        
                       // $check_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id." AND `VenueID` = '".$category_id."' AND `Role` != 'left' ";  


                       $check_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id." AND `VenueID` = '".$category_id."' AND `Role` != 'left' AND spaceId='".$spaceId."' ";


                       $check_venue_member_data = mysqli_query($con, $check_venue_member);


                      $check_venue_member_row = mysqli_fetch_row($check_venue_member_data);
                      // print_r($check_venue_member_row);
                       if($check_venue_member_row){ 
                        $already_joined = 'yes';
                        $submit_value = 'Leave';
                        $title = '<span class="custom_again_join redirectToChat join_it font-weight-bold py-3">'.$row[1].'</span>';
                       }else{
                         $already_joined = '';
                         $submit_value = 'Join';
                         $title = $row[1];
                       }
                       
                      ?>

                          <!-- <form action="/video-chat/chatroom.php" method="post">
                                <input type="text" name="createroom" value="<?php echo $row[1]; ?>" hidden>
                                <input type="text" name="createroom_id" value="<?php echo $row[0] ?>" hidden>
                                <input type="text" name="already_joined" class="already_joined" value="<?php echo $already_joined; ?>" hidden>
                                <input type="text" name="room_domain" value="<?php echo $room_domain ?>" hidden>
                                <h2><?php echo $title; ?> <span class="user-number">Members[<?php echo $row[3] ?>]</span><span class="user-number"> Live[<?php echo $row[4] ?>]</span> <span class="user-number"><input type="submit" value="<?php echo $submit_value; ?>" ></span> </h2>
                                <?php echo $row[2] ?>
                            </form> -->

                            <form action="/video-chat/chatroom.php" method="post" class="frmRoomInfo">
                              <input type="text" name="createroom" class="chatRoom" value="<?php echo $row[1]; ?>" hidden>
                                    <input type="text" name="createroom_id" class="createRoomId" value="<?php echo $row[0] ?>" hidden>
                                    <input type="text" name="already_joined" class="already_joined" value="<?php echo $already_joined; ?>" hidden>
                                    <input type="text" name="room_domain" className="roomDomain" value="<?php echo $room_domain ?>" hidden>

                        <div class="d-flex flex-column align-items-center content first_cont">
                          <span class="custom_again_join"><div class="profile">#</div></span>
                          <?php echo $title; ?>
                          <!-- <div class="join_it font-weight-bold py-3"><?php echo $title; ?></div> -->
                          <div class="d-flex align-items-center justify-content-center members_numbers generalDetail">
                            <div><?php echo $row[3] ?> Members</div>
                            <div class="mr-3 publicLbl">Public</div>
                            <div><?php echo $row[4] ?> Live</div>
                          </div>
                          <!-- <input type="text" placeholder="Enter Code"> -->
                          <div class="and_some_text mt-2"><?php echo $row[2] ?></div>
                           
                              <div class="and_some_text mt-2"><input type="submit" value="<?php echo $submit_value; ?>" >
                             
                          </div>

                        </div>
                        </form>



                        <?php
                        // $query1 = "SELECT * FROM category WHERE Name != 'General' AND type !='Private' ORDER BY liveuser DESC";
                        $query1 = "SELECT * FROM category WHERE Name != 'General' AND spaceId='".$spaceId."' ORDER BY liveuser DESC";
                        $result = mysqli_query($con, $query1);  
                        while($row=mysqli_fetch_row($result))
                        {
                          
                          $category_id = $row[0];
                          $user_id = $_SESSION['id'];
                          @$venueType = $row[7];
                          //print_r($row);
                          //echo $venueType; 
                            
                            // $check_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id." AND `VenueID` = '".$category_id."' AND `Role` != 'left' ";  
                            $check_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id." AND `VenueID` = '".$category_id."' AND `Role` != 'left' AND `Role` != 'pending' AND `Role` != 'denied' AND `Role` != 'blocked' AND `spaceId`='".$spaceId."' ";  

                            

                           $check_venue_member_data = mysqli_query($con, $check_venue_member);
                          $check_venue_member_row = mysqli_fetch_row($check_venue_member_data);

                            // check request is pending or not

                           $checkPending_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id." AND `VenueID` = '".$category_id."'  AND `Role` = 'pending' AND `spaceId`='".$spaceId."' "; 

                           $checkPending_venue_member_data = mysqli_query($con, $checkPending_venue_member);
                          $checkPending_venue_member_row = mysqli_fetch_row($checkPending_venue_member_data);


                                // check request id blocked or not
                           $checkBlockMem = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id." AND `VenueID` = '".$category_id."'  AND `status` = 'blocked' AND `spaceId`='".$spaceId."'"; 

                           $BlockMemExec = mysqli_query($con, $checkBlockMem);
                          $blockMemRes = mysqli_fetch_row($BlockMemExec);


                           $venue_type = $row[7]; 
                             
                          $venue_img = $row[9];
                          $shortNameVenue="";
                          if($venue_img == "" || $venue_img == null)
                          {
                            $getExplodeVenueName = explode(" ", $row[1]);
                            foreach ($getExplodeVenueName as $keyVanue => $valueVanue) {
                              
                              $venueFirstCharacter = substr($valueVanue, 0, 1);
                             $shortNameVenue .= $venueFirstCharacter;
                            
                            }
                            // print_r($getExplodeVenueName);
                             
                          }
                          else
                          {
                            $venue_img = currentUrl."/video-chat/venueImages/".$venue_img;
                            //echo "img found"; 
                          }


                         if($check_venue_member_row){
                       

                            $already_joined = 'yes';
                            $submit_value = 'Leave';
                            $title = '<span class="custom_again_join redirectToChat join_it font-weight-bold py-3">'.$row[1].'</span>';
                            

                            $roomStatus = "true";
                           }else if($checkPending_venue_member_row){
                             
                              //echo "pending";
                            $title = $row[1];
                            $roomStatus = "";

                           }else if($blockMemRes){
                            //echo "blocked";

                             $title = $row[1];
                             $roomStatus = "";
                           }else{
                           // echo "join";
                             $already_joined = '';
                             $submit_value = 'Join';
                             $title = $row[1];
                             $roomStatus = "";
                         }
                    ?>



                            <?php if($row[7]=="Public"){
                             
                             ?>

                            <form action="https://iungo.io/video-chat/chatroom.php" method="post" class="frmRoomInfo">
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


                        <input type="text" name="createroom" class="chatRoom" value="<?php echo $row[1] ?>" hidden>
                                <input type="text" name="createroom_id" class="createRoomId" value="<?php echo $row[0] ?>" hidden>
                                <input type="text" name="room_domain" class="roomDomain" value="<?php echo $room_domain ?>" hidden>
                                <input type="text" name="already_joined" class="already_joined" value="<?php echo $already_joined; ?>" hidden>


                               <!--  <h2><?php echo $title; ?> <span class="user-number">Members[<?php echo $row[3] ?>]</span><span class="user-number"> Live[<?php echo $row[4] ?>]</span> 
                                  <span class="user-number">
                                    <?php if($checkPending_venue_member_row)
                                    { ?>
                                      <input type="button" value="pending approval" name="pending" class="pendingBtn" />  
                                    <?php }else if($blockMemRes){?>
                                      <input type="button" value="You are blocked" name="block" class="pendingBtn" />  


                                    <?php }else {?>
                                      <input type="submit" value="<?php echo $submit_value; ?>" name="join">
                                    <?php }?>
                                </span> </h2>
                                <?php 

                                echo $row[2]; 
                                echo $venue_img;
                                echo $shortNameVenue;
                                 ?> -->

                           



                        <div class="d-flex flex-column align-items-center content">
                          <?php if($venue_img == "" || $venue_img == null) {
                                  if($roomStatus == "true"){
                                   
                                    ?>
                                     <span class="custom_again_join"><div class="profile" ><?php echo $shortNameVenue; ?></div></span>
                                    <?php
                                   
                                  }
                                  else
                                  {?>
                                    <div class="profile" ><?php echo $shortNameVenue; ?></div>
                                    <?php

                                  }

                            ?>
                          
                        <?php } else{

                                  if($roomStatus == "true"){
                                   
                                    ?>
                                     <span class="custom_again_join"> <img class="profile" src="<?php echo $venue_img; ?>"> </span> 
                                    <?php   
                                   
                                  }
                                  else
                                  {
                                    ?>
                                    <img class="profile" src="<?php echo $venue_img; ?>" />
                                    <?php
                                  }
                          ?>

                          

                        <?php } ?>
                        <?php echo $title; ?>
                          <!-- <div class="profile_name font-weight-bold py-2 text-center venueTitle"><?php echo $title; ?></div> -->
                          <div class="d-flex align-items-center justify-content-center members_numbers venueMemDetail">
                            <div><?php echo $row[3] ?> Members</div>
                            <div class="mr-3 publicLbl">

                              <?php if($venue_type == "Private") { echo "<i class='fa fa-lock privLock'></i>"; } ?>

                              <?php echo $venue_type ?>
                                
                              </div>
                            <div><?php echo $row[4] ?> Live</div>
                          </div>
                          <!-- <div class="users d-flex align-items-center">
                            <img src="images/user.jpg">
                            <img src="images/user.jpg">
                            <img src="images/user.jpg">
                            <img src="images/user.jpg">
                          </div> -->
                          <div class="and_some_text otherVenuesLst mt-0"><?php echo $row[2];  ?></div>
                          <div class="and_some_text mt-0">
                            
                              <span class="user-number">
                                    <?php if($checkPending_venue_member_row)
                                    { ?>
                                      <input type="button" value="pending approval" name="pending" class="pendingBtn btnVenue" />  
                                    <?php }else if($blockMemRes){?>
                                      <input type="button" value="You are blocked" name="block" class="pendingBtn btnVenue" />  


                                    <?php }else {?>
                                      <input type="submit" value="<?php echo $submit_value; ?>" name="join" class="btnVenue">
                                    <?php }?>
                                </span>

                          </div>
                        </div>


                         </form>

                      
                      <?php } ?>
                       
                      </div>
  </div>
<?php } ?>
                    <?php       
    if (isset($_SESSION['loggedin'])) 
      {?>
    
        <?php
             }   
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

                // a = li[i].getElementsByTagName("h2")[0];
                a = li[i].getElementsByTagName("div")[0];
               // console.log(a);
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
            $(document).ready(function(){
                $(document).on('click','.create_team_button',function(){
                  
                  $("#createNewRoom").modal('show');
                });

                $(document).on('click','.redirectToChat',function(){
                   
                   var createroom_id = $(this).parents('form').find(".createRoomId").val();
                   var createroom = $(this).parents('form').find(".chatRoom").val();
                   // var room_domain =  $(this).parents('form').find(".roomDomain").val();
                   var room_domain = 1;



                    console.log(createroom_id);
                    console.log(createroom);
                    console.log(room_domain);


                    // $.ajax({
                    //           type:'POST',
                    //           url:'chatroom.php',
                    //           data:{'createroom_id':createroom_id,'createroom':createroom,'room_domain' : room_domain},
                    //           success:function(data){
                    //             //window.location.replace("https://iungo.io/video-chat/chatroom.php");
                    //               //alert(data);
                    //               //console.log(data);
                                       
                    //               }
                    //         });
                    });
            });
        </script>
		

</body>

</html>