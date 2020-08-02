<?php
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
// Change this to your connection info.
include '../connect.php';
// include 'videoChatConstant.php';
@$spaceId = @$_SESSION['spaceId'];
if(!isset($_SESSION['id']))
{
  header("location:../index.php");
}


$category = $_POST['createroom'];
$category_id = $_POST['createroom_id'];

//echo $_POST['createroom'];


$already_joined = $_POST['already_joined'];
$user_id = $_SESSION['id'];

// echo $category ."room domain ".$room_domain."room id  ".$roomId;
$spaceName="Iungo";
$getSpaceNameTab = "";


if(isset($spaceId))
{
  $getSpaceQryTab = "select * from spaces where id='$spaceId'";
  $getqryTabExe = mysqli_query($con,$getSpaceQryTab); 
   $getSpaceTabRes = mysqli_fetch_assoc($getqryTabExe);

    $getSpaceNameTab = $getSpaceTabRes['name'];
    

    //  echo strtolower($spaceName[0])."<br>";
    
    // echo strtolower($getSpaceName);

}

if($_POST['room_domain']){
    //echo "innn";
    //echo $_POST['room_domain'];
    //$spaceName = explode('.',  $_SERVER['HTTP_HOST']);  
    //$spaceName =$getSpaceRes['name'];
    $spaceName = $getSpaceNameTab;

    //$spaceName = "kellogg";
}

if(isset($already_joined) && $already_joined == 'yes' && isset($user_id)){

  

    $getSpeciCategoryQry = "SELECT * FROM category where id='$category_id'";    

                         
    $getCategoryExec = mysqli_query($con, $getSpeciCategoryQry);
 
    $getCategoryRes = mysqli_fetch_all($getCategoryExec, MYSQLI_ASSOC);

    //print_r($getCategoryRes);
      $getMemberList;

      foreach ($getCategoryRes as $findMemKey => $memValue) {
        # code...
        $getMemberList = $memValue['members'];
      }

      $exploadeMem = explode(',',$getMemberList);

                // this is used for search for value within array 
      $getRemoveArrayKey = array_search($user_id,$exploadeMem); 


              //   remove matched array in list
      unset($exploadeMem[$getRemoveArrayKey]);

      //print_r($exploadeMem);

      $newMemList = implode(',', $exploadeMem);
      

      $queryCategoryUpd = "UPDATE `category` SET `members`='$newMemList' WHERE `id`='$category_id' AND `spaceId`='$spaceId'";
   
      mysqli_query($con, $queryCategoryUpd); 


      // $query = "UPDATE `venuemembers` SET `Role`= 'left' WHERE `UserID` = '".$user_id."' AND `VenueID` = '".$category_id."'";
	    $queryVenueMem = "UPDATE `venuemembers` SET `TotalConnections`=0,`Role`= 'left',`status`='left' WHERE `UserID` = '".$user_id."' AND `VenueID` = '".$category_id."' AND `spaceId`='".$spaceId."' ";
	 
      mysqli_query($con, $queryVenueMem); 
      
      
	   header('Location: /video-chat'); 
     die;
}

$pairUserId;
$getCategoryRes;

if(isset($category_id))
{
  
   $getcategoryQuery = "SELECT * FROM category where id='$category_id' AND spaceId='$spaceId'";    

                         
    $getCategoryExe = mysqli_query($con, $getcategoryQuery);
 
   $getCategoryRes = mysqli_fetch_all($getCategoryExe, MYSQLI_ASSOC);

   //print_r($getCategoryRes);
}

if(isset($category_id))
{
 
    $getAllMemberQuery = "SELECT accounts.name,accounts.firstname,venuemembers.*
                          FROM venuemembers
                          JOIN accounts ON venuemembers.UserID=accounts.id 
                          WHERE venuemembers.VenueID=$category_id and venuemembers.Role='member' and venuemembers.spaceId=$spaceId";    

                         
    $allMemResult = mysqli_query($con, $getAllMemberQuery);
 
   $getAllMemberList = mysqli_fetch_all($allMemResult, MYSQLI_ASSOC);
   


}

if(isset($_POST['categoryID']))
{
  
    $categoryID  = @$_POST['categoryID'];
    $sessionID = @$_POST['sessionID'];

    // echo $categoryID;
    // echo $sessionID;
    
    $selPendingMemQuery = "SELECT * FROM venuemembers where  UserID=$sessionID and VenueID=$categoryID  and Role='pending' and spaceId=$spaceId";
    $execPendingMemRes = mysqli_query($con,$selPendingMemQuery); 

    $totalPendingRows = mysqli_num_rows($execPendingMemRes);
    //echo $totalrows;
    if($totalPendingRows > 0)
    {
       // true means pending result found
      echo json_encode(true);die;
      
    }
      
      // false means pending result not found
     echo json_encode(false);die;
    


}

if(isset($_POST['getLiveUsers']))
{
        @$category = @$_POST['getLiveUsers'];
        
          $lastlive = strtotime("-5 seconds");
              $querylive_users= "SELECT *
                FROM accounts
                WHERE $lastlive<`last-live` AND `spaceId`='$spaceId' AND `category`='".$category."' 
                 GROUP BY id,`status` having `status` = 1";
              $resultlive_users = mysqli_query($con,$querylive_users);

              

              $newArray = array();
              $newArray = $resultlive_users;
              $user = array();
              $leftUserLive = array();
               
                foreach ($newArray as $key => $value) {

                  # code...
                  if($value['id'] == $_SESSION['id'])
                  {
                    //$user = $value;
                    array_push($user,$value);
                  }
                  else
                  {
                    array_push($leftUserLive,$value); 
                  }
                }

    echo json_encode($leftUserLive);die;
 }

if(isset($_POST['matchedUserId']))
{
  $pairUserId =  $_POST['matchedUserId'];
     $getAllPairQuery = "SELECT * FROM accounts WHERE id=$pairUserId";    

                         
    $allPairlResult = mysqli_query($con, $getAllPairQuery);
 
   $getAllPairList = mysqli_fetch_all($allPairlResult, MYSQLI_ASSOC);
   // echo "<pre>";
   // print_r($getAllPairList);die;
   echo json_encode($getAllPairList);die;
  //echo $_POST['matchedUserId'];die;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Video Chat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Video Chat.">
    <meta name="author" content="Brian Mau">
    <!-- <link href="/css/main.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/chatroom.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" />
    <link rel="stylesheet" href="css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" >
     -->


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
   <div class="fluid-container">
       <div class="row mainrow">
           <div class="custom-scrollbar">
						<?php include_once('sidebar.php'); ?>
                        <div class="rooms">
							
                           <?php /** <ul>
                                <?php
                                    $query = "SELECT * FROM category where createdBy = '".$_SESSION['name']."'";
                                    $result = mysqli_query($con,$query);
                                    while($row=mysqli_fetch_row($result))
                                    {?>


                               <li> <form action="/video-chat/chatroom.php" method="post">
                                    <input type="text" name="createroom" value="<?php echo $row[0] ?>" hidden>
                                    <input type="text" name="room_domain" value="<?php echo $room_domain ?>" hidden>

                                    <input type="submit" value="<?php echo $row[1] ?>" >
                                    
                                </form>
                               </li>


                            <?php   }
                                ?>
                                <!-- <li><a href="/chatroom?createroom=Music">Music</a></li>
                                <li><a href="/chatroom?createroom=Tech">Tech</a></li>
                                <li><a href="/chatroom?createroom=Games">Games</a></li>
                                <li><a href="/chatroom?createroom=Dance">Dance</a></li> -->
                            </ul> **/ ?>
                            <!-- <button class="create" onclick="viewform()">Create Room</button>
                            <div class="addroom" id="addroom">
                                <form action="addroom.php" method="POST">
                                    <input type="text" name="roomname" placeholder="Room Name" required>
                                    <br>
                                    <input type="text" name="roomdesc" placeholder="Room Description" required>
                                    <br>
                                    <br>
                                    <input type="submit" value="Add Room">
                                </form>
                            </div> -->
                        </div>
                    </div>
           <div class="" id="mylist">
                  <a href="javascript:void(0);" class="icon btnIcon">
                    <i class="fa fa-bars"></i>
                  </a>

                  <div class="chatleft">

                            <div class="col-12 navigate-panel"><?php echo "<label class='navigate-prev'><a  href='/video-chat/index.php' >".$getSpaceNameTab."</a> <label class='nav-divider'> <i class='fa fa-angle-double-right'></i></label> </label> <label class='navigate-cur'>".@$category ?></label></div>
                  </div>
                <div class="row chatroom">
                    <div class="col-lg-9 col-md-12 col-sm-12 chatroom-col-main">
                        
                        <div class="row video">
                            <div id="otEmbedContainer" style="width:800px; height:416.25px" class="chatroom-video-embad"></div>
                        </div>
                        <div class="row chatbtns">
                          
                            <div class="col-lg-3 col-md-12 col-sm-12" style="display: none"><button class="end" id="end" onclick="endcall1()">Take a Break</button></div>
                            <div class="col-lg-6 col-md-12 col-sm-12"><button class="next chatroom-btn" onclick="nextperson()">Connect with somebody else</button></div>
                            
                        </div>
                         <div style="font-size: small;margin-top: 10px">

                         <?php  
                           
                              $lastlive = strtotime("-5 seconds");
                              $querylive_users= "SELECT *
                                FROM accounts
                                WHERE $lastlive<`last-live` AND `category`='".$category."' 
                                 GROUP BY id,`status` having `status` = 1";
                              $resultlive_users = mysqli_query($con,$querylive_users);

                              

                              $newArray = array();
                              $newArray = $resultlive_users;
                              $user = array();
                              $leftUserLive = array();
                               
                                foreach ($newArray as $key => $value) {

                                  # code...
                                  if($value['id'] == $_SESSION['id'])
                                  {
                                    //$user = $value;
                                    array_push($user,$value);
                                  }
                                  else
                                  {
                                    array_push($leftUserLive,$value); 
                                  }
                                }
                              
                                ?>
                               
                              <div style="display: none" class="liveUserHeading"><b>Live users :</b></div>
                           

                            <div class="flex_contents liveUserInfoCls">
                              <!--Add a dynamica live user info jquery-->

                                <!-- <div>item 1</div>
                                <div>item 2</div>
                                <div>item 3</div>
                                </div> -->
                               
                              </div>

                              <!-- <div class="chatroom_imag text-center">
                                  
                              </div> -->


                             

                              <div class="flex-container-liveuser owl-carousel">
                               <!--Add dynamic img from jQuery -->
                              </div>


                              <!-- <br/> -->
                            <b style="display: none">Common questions:</b><br />
                            
                           
                          
                            <!-- <b>1.</b> If you see the name of your connection on the top right, but your connection does not appear, give them about 30 seconds to connect. After that, they are probably gone...Relax and just click on <b><i>"Connect with somebody else"</i></b><br />
                            <b>2.</b> Don't forget to click on <b><i>"Click to start video chat"</i></b>, or you will leave the other person waiting<br />
                            <br />
                            <b>Instructions:</b><br />
                            <b>1.</b> Click on <b><i>"Click to start video chat"</i></b>. You will automatically be connected. <br />
                            <i>(This typically takes only a few seconds, but could take a minute or two if live users is low)</i><br /> 
                            <b>2.</b> When you want to connect with somebody else, politely let your connection know. Then click on <b><i>"Connect with somebody else"</i></b> <br /> -->
                    
                                
                </div>
                        
                    </div>


<?php 

        $user_id = $_SESSION['id'];   
		              
        $query3 = "SELECT `roomId`,`id` FROM `accounts` WHERE `id` = '".$user_id."'"; 
        $resultID = mysqli_query($con, $query3);
        $result_query3 = mysqli_fetch_row($resultID);
        $id= $result_query3[1];
        $roomId = $result_query3[0];
		
		$last_live_query = "SELECT * FROM accounts WHERE id = '".$_SESSION['id']."'";
			$last_live_query_data = mysqli_query($con, $last_live_query);
			$last_live__row = mysqli_fetch_row($last_live_query_data);
			
			if(isset($last_live__row[12]) && !empty($last_live__row[12])){
				$Last_Connection = $last_live__row[12];
			}else{
				$Last_Connection = time();
			}
		
		
		$current_date_time = time();
		
		 $check_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$id." AND `VenueID` = '".$category_id."' AND `spaceId`='".$spaceId."' ";
       
			$check_venue_member_data = mysqli_query($con, $check_venue_member);
			$check_venue_member_row = mysqli_fetch_row($check_venue_member_data);
		
		 if($check_venue_member_row){ 
		  // echo "iff innn";
    //   echo $category;
			$current_connection = $check_venue_member_row[5];
			$total_connection = (int) $current_connection + 1;
			
       $query;
        if($getCategoryRes[0]['type'] == "Private" && $check_venue_member_row[7] == "pending")
       {
          

         // $query = "UPDATE `venuemembers` SET  `Role`= 'member' WHERE `UserID` = '".$id."' AND `VenueID` = '".$category_id."'";

       }
       else
       {  
          
          
          /// if member is owner or moderator
          // if($getCategoryRes[0]['type'] == "Private" && ($check_venue_member_row[7] == "owner" || $check_venue_member_row[7] == "moderator"))
          // {
          //   $query = "INSERT INTO venuemembers (UserID, VenueID, DateJoined, LastConnection, TotalConnections, ConnectionTime, Role) VALUES ('$id','$category_id','$current_date_time','$current_date_time',0, '', 'member')";
          // }
          // else
          // {
            
            $query = "UPDATE `venuemembers` SET  `Role`= 'member' WHERE `UserID` = '".$id."' AND `VenueID` = '".$category_id."'";            
         // }


       }
			  
			   mysqli_query($con, $query); 

		  }else{ 
			 //echo "else innn";
       // $venuemembers_query = "INSERT INTO venuemembers (UserID, VenueID, DateJoined, LastConnection, TotalConnections, ConnectionTime, Role) VALUES ('$id','$category_id','$current_date_time','$current_date_time',0, '', 'member')";
      // echo $category;
       $venuemembers_query;
       if($getCategoryRes[0]['type'] == "Private")
       {
              
          $venuemembers_query = "INSERT INTO venuemembers (UserID, VenueID, DateJoined, LastConnection, TotalConnections, ConnectionTime, Role,spaceId) VALUES ('$id','$category_id','$current_date_time','$current_date_time',0, '', 'pending','$spaceId')";
       }
       else
       {
       
          $venuemembers_query = "INSERT INTO venuemembers (UserID, VenueID, DateJoined, LastConnection, TotalConnections, ConnectionTime, Role,spaceId) VALUES ('$id','$category_id','$current_date_time','$current_date_time',0, '', 'member','$spaceId')";
       }

			 
		 
			   $result = mysqli_query($con,$venuemembers_query);
		  }  
		
		
		
		
        // echo $roomId;
        include 'members.php';           
        $query = "UPDATE `accounts` SET `status`=1,`category`='".$category."', `spaceName`= '".$spaceName."' WHERE `id` = '".$user_id."'";
         mysqli_query($con, $query);
		$lastlive = strtotime("-5 seconds"); 
 
    if($spaceName != "Iungo"){


        // if(strtolower($spaceName[0]) != strtolower($getSpaceName)){
            $query1 = "SELECT COUNT(roomId), roomId, category, spaceName
                    FROM accounts
                    WHERE
                    `category`='".$category."' AND `spaceName` = '$spaceName' AND `roomId` != '".$roomId."' AND $lastlive<`last-live`
                    GROUP BY roomId, category, spaceName
                    HAVING COUNT(roomId)=1";
        }
        else{
            $query1 = "SELECT COUNT(roomId), roomId, category, spaceName
                    FROM accounts
                    WHERE
                    `category`='".$category."' AND `roomId` != '".$roomId."' AND $lastlive<`last-live`
                    GROUP BY roomId, category, spaceName
                    HAVING COUNT(roomId)=1";
                    // echo $roomId;
        }
        $result = mysqli_query($con, $query1);
        $row = mysqli_fetch_row($result);
        if($row){
//            echo $row[0]."[0] ".$row[1]."[1] ".$row[2]."<br>";
              $roomId = $row[1];
              $query2 = "UPDATE `accounts` SET `roomId`='".$roomId."' WHERE `id` = '".$user_id."'";
              mysqli_query($con, $query2);
        }
        else{
            $query3 = "SELECT `id` FROM `accounts` WHERE `id` = '".$user_id."'"; 
            $resultID = mysqli_query($con, $query3);
            $roomId = md5((mysqli_fetch_row($resultID))[0]."".strtotime("now"));
            $query2 = "UPDATE `accounts` SET `roomId`='".$roomId."' WHERE `id` = '".$user_id."'";
              mysqli_query($con, $query2);
        }
           
        
        
    ?>


                    <div class="col-lg-3 col-sm-12 col-md-12 desc cust-your-connection">
                       <!--  <div class="row about-second">
                                    <h1 id="othername"></h1>
                                    <div id="aboutother"></div>
                                
                        </div> -->
						<!-- <div class="row about-me">
							<h1>About me</h1>
							<?php

							$query = "SELECT aboutyou FROM accounts Where id = '".$_SESSION['id']."'";
							$row = mysqli_query($con,$query);
							$result = mysqli_fetch_row($row);
							echo $result[0];
							?>
						</div> -->
						<div class="live_user_list_mn">
							<h3>Your connection</h3>
							     <div class='congrats' style="display: none"></div>
                     
                       <div class="card shadow-sm connectedUserCard" style="width: 18rem;margin-top: 10px;display: none">
                                
                                <div class="imgUser">
                                    <!--add dynamic image from jQuery-->
                                </div>

                              
                             
                              <div class="userProfileName" id="flName"><b>firstname lastname</b></div>
                              <div class="userProfilePronounce" id="pronounceUser">pronounce</div>
                             <!--  <hr /> -->
                                  <div class="live-person-card">
                                   <!--  <div class="whoAmITitle">Who am i</div>  -->
                                   <div class="profile-who-content">
                                       <div class="whoAmITitle"><b>Who am i</b></div>

                                    <div class="whoAmIContent" id="whoI"></div>
                                  </div>
                                  <div class="like-talk">
                                    <div class="whoAmITitle commonTitle"><b>what i like to talk about</b></div>
                                    
                                    <div class="whoAmIContent" id="whatILike">lik tak valu</div>
                                  </div>
                                  </div>
                                </div>
                
                     
						</div>
              
                    </div>
               <!--  <div>
                  <br /><br />
                  
                </div> -->
                
               
                </div>
                
            
           
    
    
           </div>
       </div>
   </div>
   
   
    
    <!-- 

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="js/owl.carousel.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/fontawesome.min.js" ></script> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.2/underscore-min.js"></script>


<script>
    $(function() {
        var numberOfStars = 200;
        for (var i = 0; i < numberOfStars; i++) {
          $('.congrats').append('<div class="blob fa fa-star ' + i + '"></div>');
      }   
      animateText();
      animateBlobs();
  });
    $('.congrats').click(function() {
        reset();
        animateText();
        animateBlobs();
    });
    function reset() {
        $.each($('.blob'), function(i) {
            TweenMax.set($(this), { x: 0, y: 0, opacity: 1 });
        });
        TweenMax.set($('h1'), { scale: 1, opacity: 1, rotation: 0 });
    }
    function animateText() {
        TweenMax.from($('h1'), 0.8, {
            scale: 0.4,
            opacity: 0,
            rotation: 15,
            ease: Back.easeOut.config(4),
        });
    }
    function animateBlobs() {
        var xSeed = _.random(350, 380);
        var ySeed = _.random(120, 170);
        $.each($('.blob'), function(i) {
            var $blob = $(this);
            var speed = _.random(1, 5);
            var rotation = _.random(5, 100);
            var scale = _.random(0.8, 1.5);
            var x = _.random(-xSeed, xSeed);
            var y = _.random(-ySeed, ySeed);
            TweenMax.to($blob, speed, {
                x: x,
                y: y,
                ease: Power1.easeOut,
                opacity: 0,
                rotation: rotation,
                scale: scale,
                onStartParams: [$blob],
                onStart: function($element) {
                    $element.css('display', 'block');
                },
                onCompleteParams: [$blob],
                onComplete: function($element) {
                    $element.css('display', 'none');
                }
            });
        });
    }
</script>

    
    <?php 

    if(isset($_SESSION['loggedin'])){
    if(isset($_POST['createroom']))
          {
            
            $room_domain = $_POST['room_domain'];
    ?>
            <script src="https://tokbox.com/embed/embed/ot-embed.js?embedId=6dbb8920-351e-42af-8b0d-8fe31fbc8600&room=<?php echo $category.$room_domain.$roomId?>"></script>
       <?php }}?>

    <script>
        var userConnectStatus = false;

              $(document).ready(function(){
                $(".owl-carousel").owlCarousel();
                $('.connectedUserCard').css('display', 'none');
                 $('.liveUserInfoCls').css('display','none');
                  $(function () {
        				  $('[data-toggle="tooltip"]').tooltip();
        				});

                  var owl = $('.owl-carousel');

                  owl.owlCarousel({
                      loop:true,
                      autowidth: true,
                      margin:10,
                      nav:true,
                      responsive:{
                          0:{
                              items:1
                          },
                          600:{
                              items:1
                          },
                          1000:{
                              items:7
                          }
                      }
                  });

                  owl.on('mousewheel', '.owl-stage', function (e) {
                    if (e.deltaY>0) {
                        owl.trigger('next.owl');
                    } else {
                        owl.trigger('prev.owl');
                    }
                    e.preventDefault();
                });

                //$('.connectedUserCard').hide();
                var matchUserId;
                  // var chatroomID = $('.chatroomId').val();
                  // console.log(chatroomID);

                  // $('.sidebarMenu').on('click',function(){
                  //   var chatroomID = $( "li.sidebarMenu" ).find( ".chatroomId" ).val();
                  //     console.log(chatroomID);
                  // });


                   var categoryID = '<?php echo $category_id; ?>';
                   var sessionID = '<?php echo $_SESSION["id"] ?>';
                   
                   console.log(categoryID);
                   console.log(sessionID);
                  
                  function findPrivateRoomJoin(){
                       $.ajax({
                            type:'POST',
                            url:'chatroom.php',
                            data:{'categoryID':categoryID,'sessionID':sessionID},
                            success:function(data){
                                //alert(data);
                                //console.log(data);
                                    if(data)
                                    {
                                      userConnectStatus = data;
   
                                    }
                                    else
                                    {
                                      // $('.liveUserHeading').css('display','none');
                                      // $('.liveUserInfoCls').css('display','none');
                                    }
                                     
                                 //location.reload(true);
                                }
                                
                             
                        });
                  }

                      


                  function getLiveUserList(){
                    var categoryName = '<?php echo $category ?>';

                  
                       $.ajax({
                            type:'POST',
                            url:'chatroom.php',
                            data:{'getLiveUsers':categoryName},
                            success:function(data){
                                //alert(data);
                                //console.log(data);
                                    if(data)
                                    {
                                      $('.liveUserHeading').css('display','block');

                                      $('.liveUserInfoCls').css('display','block');

                                       var liverUserInfo = jQuery.parseJSON(data);
                                      var infoLength =  Object.keys(liverUserInfo).length;
                                      if(infoLength == 0)
                                      {
                                        $('.connectedUserCard').css('display', 'none');
                                      }
                                      //console.log(infoLength);
                                       $('.flex-container-liveuser').html("");
                                       $.each( liverUserInfo, function( key, value ) {
                                       		var fullName = value.firstname +" "+ value.name;
                                       		// console.log(fullName);
                                         //  console.log(value.profilePicture);
                                         //  console.log(value.firstname);
                                            if(value.profilePicture == "" || value.profilePicture == null)
                                                 {

                                                    var getVal = value.firstname.charAt(0) + value.name.charAt(0);
                                                     
                                                      var profileImageText = getVal.toUpperCase();
                                                      // console.log("img not found");
                                                     $(".flex-container-liveuser").append('<div data-toggle="tooltip" data-placement="bottom" title="'+fullName+'" ><p class="liveuser-img-lst name-border">'+profileImageText+'</p> </div>');
                                                  
                                                 }
                                                 else
                                                 {

                                                var imgname = '<?php echo  currentUrl."/video-chat/profileImages/" ?>' + value.profilePicture; 

                                                $(".flex-container-liveuser").append('<div data-toggle="tooltip" data-placement="bottom" title="'+fullName+'"><img src="'+imgname+'" class="liveuser-img-lst img-border" alt="..." /></div>');

                                                 
                                                    
                                                 }
                                       
                                        // $(".liveUserInfoCls").append("<div class='flLiveUserName' style='display:block'>"+value.firstname+ " " +value.name +"</div>");
                                          
                                        });
                                          
                                        
                                        
                                    }
                                    else
                                    {
                                      $('.liveUserHeading').css('display','none');
                                      $('.liveUserInfoCls').css('display','none');
                                      

                                    }
                                     
                                 //location.reload(true);
                                }
                                
                             
                        });

                  }

                getLiveUserList();
                  setInterval(function(){
                    getLiveUserList();
                    findPrivateRoomJoin();

                 }, 3000);

              })

            function endcall1() {
                document.getElementById("end").click();
            }
            function nextperson(){
                 location.reload(); 
            }
            // console.log(<?php echo $category.$room_domain.$roomId ?>)
            window.onunload = function() {
                alert('Bye.');
            }

            // var xyz = setInterval("showTime()", 1000);



            function showTime(){
              // console.log("showTime")
              setInterval(function(){
                console.log(userConnectStatus);

                  if(userConnectStatus == "true")
                  {

                    window.location = "joinVenue.php";
                      //console.log("wait");

                  }
                  else
                  {
                    console.log("done");
                  

                      var xmlhttp = new XMLHttpRequest();
                      xmlhttp.onreadystatechange = function() {
                          if (this.readyState == 4 && this.status == 200) {
                            if(this.responseText != "")
                              {
                                var data = this.responseText.split("^");
                                //console.log(data);
                                // document.getElementById("aboutother").innerHTML = data[1];
                                // document.getElementById("othername").innerHTML = data[0];
                                //console.log(this.responseText);
                                var congratsCount = 0;
                                matchUserId = data[0];

                                 $.ajax({


                                            type:'POST',
                                            url:'chatroom.php',
                                            data:{'matchedUserId':matchUserId},
                                            success:function(data){
                                                //alert(data);

                                                if(data)
                                                {
                                                  $('.connectedUserCard').css('display','block');
                                                  
                                                  // $('.congrats').append('display','block');
                                                   
                                                 //$('.connectedUserCard').show();
                                                }
                                                else
                                                {
                                                 //$('.connectedUserCard').hide();
                                                  $('.connectedUserCard').css('display', 'none');

                                                


                                                }

                                                var matchedUserInfo = jQuery.parseJSON(data);
                                                
                                                 // console.log(matchedUserInfo[0].firstname);
                                                 // console.log(matchedUserInfo[0].name);
                                                 var firstName = matchedUserInfo[0].firstname;
                                                 var lastName = matchedUserInfo[0].name;
                                                 var pronouns = matchedUserInfo[0].pronouns;
                                                 var whoAmI = matchedUserInfo[0].whoAmI;
                                                 var likeToTalkAbout =  matchedUserInfo[0].likeToTalkAbout;
                                                 var profilePicture = matchedUserInfo[0].profilePicture;


                                                 if(profilePicture == "" || profilePicture == null)
                                                 {

                                                    var getVal = firstName.charAt(0) + lastName.charAt(0);
                                                     
                                                      var profileImageText = getVal.toUpperCase();
                                                      // console.log("img not found");
                                                     $('.imgUser').replaceWith('<div class="patner-image main-patner-image"><div class="userLiveNameOther rounded-circle">'+profileImageText+'</div> </div>');
                                                  
                                                 }
                                                 else
                                                 {

                                                var imgname = '<?php echo  currentUrl."/video-chat/profileImages/" ?>' + profilePicture; 
                                                $('.imgUser').replaceWith('<div class="patner-image"><img src="'+imgname+'" class="card-img-top rounded-circle" alt="..." /></div>');

                                                 
                                                    
                                                 }

                                                document.getElementById("flName").innerHTML = firstName + ' ' + lastName;
                                                document.getElementById("pronounceUser").innerHTML = pronouns;
                                                document.getElementById("whoI").innerHTML = whoAmI;
                                                document.getElementById("whatILike").innerHTML = likeToTalkAbout;

                                                
                                                 
                                                // console.log(data.response.firstname);
                                                // console.log(data.response.name);
                                                        
                                                 //location.reload(true);
                                                }
                                                
                                             
                                        });
                              }
                              else{
                                // document.getElementById("aboutother").innerHTML = "";
                                // document.getElementById("othername").innerHTML = "";
                                //$('.connectedUserCard').hide();
                                 $('.connectedUserCard').css('display', 'none');
                              }
                              // console.log("---hi---");
                          }
                      };
                      xmlhttp.open("GET", "gethint.php?q=" + "<?php echo $roomId."^".$category."^".$category_id ?>", true);
                      xmlhttp.send();
                  }
              // console.log("---fun---");
              },3000);
                  
            }


            showTime();
    </script>
</body>
</html>

           
    
    