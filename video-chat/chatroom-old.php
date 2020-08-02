<?php
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
// Change this to your connection info.
include '../connect.php';
// include 'videoChatConstant.php';

$category = $_POST['createroom'];
$category_id = $_POST['createroom_id'];
$already_joined = $_POST['already_joined'];
$user_id = $_SESSION['id'];

if(isset($already_joined) && $already_joined == 'yes' && isset($user_id)){
	$query = "UPDATE `venuemembers` SET `Role`= 'left' WHERE `UserID` = '".$user_id."' AND `VenueID` = '".$category_id."'";
	mysqli_query($con, $query); 
	 header('Location: /video-chat'); 
     exit();
}

$spaceName="Iungo";
if($_POST['room_domain']){
    $spaceName = "kellogg";
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
    <link href="/css/main.css" rel="stylesheet">
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
   <div class="fluid-container">
       <div class="row mainrow">
           <div class="col-3">
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
           <div class="col-9">
                <div class="row chatroom">
                    <div class="col-9">
                        <div class="row chatleft">
                            <div class="col-12"><h1><?php echo "<a href='/video-chat/index.php' style='text-decoration: none;font-weight: 800;color:black;font-size:2.5rem'>".$spaceName."</a> > ".$category ?></h1></div>
                        </div>
                        <div class="row video">
                            <div id="otEmbedContainer" style="width:800px; height:416.25px"></div>
                        </div>
                        <div class="row chatbtns">
                            <div class="col-3"></div>
                            <div class="col-3"><button class="end" id="end" onclick="endcall1()">Take a Break</button></div>
                            <div class="col-6"><button style="padding-left: 0;width: 70%;padding-right: 0;" class="next" onclick="nextperson()">Connect with somebody else</button></div>
                            
                        </div>
                         <div style="font-size: small;margin-top: 10px">
                            <b>Common questions:</b><br />
                            <b>1.</b> If you see the name of your connection on the top right, but your connection does not appear, give them about 30 seconds to connect. After that, they are probably gone...Relax and just click on <b><i>"Connect with somebody else"</i></b><br />
                            <b>2.</b> Don't forget to click on <b><i>"Click to start video chat"</i></b>, or you will leave the other person waiting<br />
                            <br />
                            <b>Instructions:</b><br />
                            <b>1.</b> Click on <b><i>"Click to start video chat"</i></b>. You will automatically be connected. <br />
                            <i>(This typically takes only a few seconds, but could take a minute or two if live users is low)</i><br /> 
                            <b>2.</b> When you want to connect with somebody else, politely let your connection know. Then click on <b><i>"Connect with somebody else"</i></b> <br />
                    
                                
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
		
		 $check_venue_member = "SELECT * FROM venuemembers WHERE `UserID` = ".$id." AND `VenueID` = '".$category_id."' ";
       
			$check_venue_member_data = mysqli_query($con, $check_venue_member);
			$check_venue_member_row = mysqli_fetch_row($check_venue_member_data);
			
		 if($check_venue_member_row){ 
		 
			$current_connection = $check_venue_member_row[5];
			$total_connection = (int) $current_connection + 1;
			
			 $query = "UPDATE `venuemembers` SET  `Role`= 'member' WHERE `UserID` = '".$id."' AND `VenueID` = '".$category_id."'";
			  
			mysqli_query($con, $query); 
		  }else{ 
			
			 $venuemembers_query = "INSERT INTO venuemembers (UserID, VenueID, DateJoined, LastConnection, TotalConnections, ConnectionTime, Role) VALUES ('$id','$category_id','$current_date_time','$current_date_time',0, '', 'member')";
		 
			$result = mysqli_query($con,$venuemembers_query);
		  }  
		
		
		
		
        // echo $roomId;
        include 'members.php';           
        $query = "UPDATE `accounts` SET `status`=1,`category`='".$category."', `spaceName`= '".$spaceName."' WHERE `id` = '".$user_id."'";
         mysqli_query($con, $query);
		$lastlive = strtotime("-5 seconds"); 
        if($spaceName != "Iungo"){
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


                    <div class="col-3 desc">
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
							<h3>Live Users</h3>
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
              $leftUser = array();
               
                foreach ($newArray as $key => $value) {

                  # code...
                  if($value['id'] == $_SESSION['id'])
                  {
                    //$user = $value;
                    array_push($user,$value);
                  }
                  else
                  {
                    array_push($leftUser,$value); 
                  }
                }


                foreach ($user as $key => $value) {
                    // echo @$value['firstname'];
                    // echo @$value['profilePicture'];

                  # code...
                   @$getFirstnameLatter = @$value['firstname']; 
                                @$getLastnameLatter =  @$value['name'];
                                @$uId = @$value['id'];

                                $imgLiveUserpath = currentUrl."/video-chat/profileImages/".$value['profilePicture']; 
                            

                                   
                          ?>


                                <div class="card" style="width: 18rem;margin-top: 10px">
                                <?php 
                                 if(isset($value['profilePicture']) && !empty($value['profilePicture']))
                                  {  
                                    if($uId == $_SESSION['id'])
                                    {
                                   ?> 
                                    <div><img src="<?php echo $imgLiveUserpath ?>" class="card-img-top" alt="..." /></div>
                                  <?php }
                                    else{?>
                                      <div class="roundimage"><img src="<?php echo $imgLiveUserpath ?>" class="card-img-top rounded-circle" alt="..." /></div>

                              <?php }} else{
                                            if($uId == $_SESSION['id'])
                                            {
                                ?>
                               
                                          <div><div class="userLiveName"><?php  echo strtoupper(@$getFirstnameLatter['firstname']).strtoupper(@$getLastnameLatter['name']); ?></div> </div>
                                        <?php }
                                        else{ ?>
                                          <div class="roundimage"><div class="userLiveNameOther rounded-circle"><?php  echo strtoupper(@$getFirstnameLatter['firstname']).strtoupper(@$getLastnameLatter['name']); ?></div> </div>
                              <?php }}?>
                              <div class="userProfileName"><b><?php echo $value['firstname'].' '. $value['name']; ?></b></div>
                              <div class="userProfilePronounce"><?php echo $value['pronouns'] ?></div>
                              <hr />
                                  <div class="">
                                   <!--  <div class="whoAmITitle">Who am i</div>  -->
                                <div class="whoAmITitle"><b>Who am i</b></div>

                                    <div class="whoAmIContent"><?php echo $value['whoAmI'] ?></div>

                                    <div class="whoAmITitle commonTitle"><b>what i like to talk about</b></div>
                                    
                                    <div class="whoAmIContent"><?php echo $value['likeToTalkAbout'] ?></div>
                                  </div>
                                </div>
                                <?php
                }


                foreach ($leftUser as $key => $value) {
                 
                  @$getFirstnameLatter = @$value['firstname']; 
                                @$getLastnameLatter =  @$value['name'];
                                @$uId = @$value['id'];

                                $imgLiveUserpath = currentUrl."/video-chat/profileImages/".$value['profilePicture']; 
                            

                                   
                          ?>


                                <div class="card" style="width: 18rem;margin-top: 10px">
                                <?php 
                                 if(isset($value['profilePicture']) && !empty($value['profilePicture']))
                                  {  
                                    if($uId == $_SESSION['id'])
                                    {
                                   ?> 
                                    <div><img src="<?php echo $imgLiveUserpath ?>" class="card-img-top" alt="..." /></div>
                                  <?php }
                                    else{?>
                                      <div class="roundimage"><img src="<?php echo $imgLiveUserpath ?>" class="card-img-top rounded-circle" alt="..." /></div>

                              <?php }} else{
                                            if($uId == $_SESSION['id'])
                                            {
                                ?>
                               
                                          <div><div class="userLiveName"><?php  echo strtoupper(@$getFirstnameLatter['firstname']).strtoupper(@$getLastnameLatter['name']); ?></div> </div>
                                        <?php }
                                        else{ ?>
                                          <div class="roundimage"><div class="userLiveNameOther rounded-circle"><?php  echo strtoupper(@$getFirstnameLatter['firstname']).strtoupper(@$getLastnameLatter['name']); ?></div> </div>
                              <?php }}?>
                              <div class="userProfileName"><b><?php echo $value['firstname'].' '. $value['name']; ?></b></div>
                              <div class="userProfilePronounce"><?php echo $value['pronouns'] ?></div>
                              <hr />
                                  <div class="">
                                   <!--  <div class="whoAmITitle">Who am i</div>  -->
                                <div class="whoAmITitle"><b>Who am i</b></div>

                                    <div class="whoAmIContent"><?php echo $value['whoAmI'] ?></div>

                                    <div class="whoAmITitle commonTitle"><b>what i like to talk about</b></div>
                                    
                                    <div class="whoAmIContent"><?php echo $value['likeToTalkAbout'] ?></div>
                                  </div>
                                </div>
                                <?php

                }



							
							?>
						</div>
              
                    </div>
                <div><br /><br /></div>
                
               
                </div>
                
            
           
    
    
           </div>
       </div>
   </div>
   
   
    
    <!-- 

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <?php 
    if(isset($_SESSION['loggedin'])){
    if(isset($_POST['createroom']))
          {
            
            $room_domain = $_POST['room_domain'];
    ?>
            <script src="https://tokbox.com/embed/embed/ot-embed.js?embedId=6dbb8920-351e-42af-8b0d-8fe31fbc8600&room=<?php echo $category.$room_domain.$roomId?>"></script>
       <?php }}?>
    <script>
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
             var xmlhttp = new XMLHttpRequest();
                  xmlhttp.onreadystatechange = function() {
                      if (this.readyState == 4 && this.status == 200) {
                        // if(this.responseText != "")
                        //   {
                        //     var data = this.responseText.split("^");
                        //     document.getElementById("aboutother").innerHTML = data[1];
                        //     document.getElementById("othername").innerHTML = data[0];
                        //     console.log(this.responseText);
                        //   }
                        //   else{
                        //     document.getElementById("aboutother").innerHTML = "";
                        //     document.getElementById("othername").innerHTML = "";
                        //   }
                          // console.log("---hi---");
                      }
                  };
                  xmlhttp.open("GET", "gethint.php?q=" + "<?php echo $roomId."^".$category."^".$category_id ?>", true);
                  xmlhttp.send();
              // console.log("---fun---");
              }, 3000);
                  
            }
            showTime();
    </script>
</body>
</html>

           
    
    