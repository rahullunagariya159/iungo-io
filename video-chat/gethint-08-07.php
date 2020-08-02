<?php
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
// Change this to your connection info.
include '../connect.php';
// echo $roomId;
$request = $_REQUEST["q"];

@$spaceId = @$_SESSION['spaceId'];

$requestArray = explode("^",$request);
$roomId = $requestArray[0];
$category = $requestArray[1];
$category_id = $requestArray[2];


$lastlive = strtotime('now');
$query = "UPDATE accounts SET `last-live` =$lastlive WHERE id = '".$_SESSION['id']."'";
mysqli_query($con,$query);
$query ="SELECT id,aboutyou FROM accounts Where roomId = '".$roomId."' HAVING id != '".$_SESSION['id']."'";
$row = mysqli_query($con,$query);
if($result = mysqli_fetch_row($row))
{
	echo $result[0]."^".$result[1];
	echo "<br>";
	
	//if Users are connected

	//stats variables 
	$participants = $_SESSION['id']."-".$result[0];
	$participants = explode("-", $participants);
	sort($participants);
	$user1 = $participants[0];
	$user2 = $participants[1];
	$participants = implode("-",$participants);
	$chattime = 0;
	$ConnectionTime = 0;
	$room = $category;
	
	
	$user_query_1 ="SELECT * FROM accounts Where id = '".$user1."'";
	$user_row_1 = mysqli_query($con,$user_query_1);
	if($user_result_1 = mysqli_fetch_row($user_row_1)){
		$user_id_1 = $user_result_1[0];
	}
	
	$user_query_2 ="SELECT * FROM accounts Where id = '".$user2."'";
	$user_row_2 = mysqli_query($con,$user_query_2);
	if($user_result_2 = mysqli_fetch_row($user_row_2)){
		$user_id_2 = $user_result_2[0];
	}
	
	
	//Check if recording is started
	$check_stats_query = "SELECT * FROM stats WHERE roomId = '$roomId' AND participants = '$participants' LIMIT 1";
	$check_stats_result = mysqli_query($con,$check_stats_query);
	$check_stats_row = mysqli_fetch_row($check_stats_result);
	
	
	if($check_stats_row)
	{
		
		//Update time every 3 seconds
		$update_stats_query = "UPDATE stats SET chattime = chattime + 3 WHERE roomId = '$roomId' AND participants = '$participants' LIMIT 1";
		mysqli_query($con,$update_stats_query);
		
		$get_chattime_query = "SELECT * FROM stats WHERE roomId = '$roomId' AND participants = '$participants' LIMIT 1";
		$get_chattime_result = mysqli_query($con,$get_chattime_query);
		$get_chattime = mysqli_fetch_row($get_chattime_result);
		$final_chattime = $get_chattime[2];
		
		
		
		$get_user_chat_query_1 ="SELECT * FROM venuemembers WHERE `UserID` = ".$user_id_1." AND `VenueID` = '".$category_id."' ";
		$get_user_chat_data_1 = mysqli_query($con,$get_user_chat_query_1);
		if($get_user_chat_result_1 = mysqli_fetch_row($get_user_chat_data_1)){
			$previous_chat_time_1 = $get_user_chat_result_1[6];
			$already_added_time_1 = $get_user_chat_result_1[8];
		}
		
		if($already_added_time_1 == 0){
			
			$final_chattime_user_1 = (int) $final_chattime + (int) $previous_chat_time_1;
		
			$query_1 = "UPDATE `venuemembers` SET `ConnectionTime`='".$final_chattime_user_1."',`already_added_connection_time`=1 WHERE `UserID` = '".$user_id_1."' AND `VenueID` = '".$category_id."'";
			mysqli_query($con, $query_1);
			
		}else{
			
			$query_1 = "UPDATE `venuemembers` SET ConnectionTime = ConnectionTime + 3 WHERE `UserID` = '".$user_id_1."' AND `VenueID` = '".$category_id."'";
			mysqli_query($con, $query_1);
			
		}
		
		
	
		$get_user_chat_query_2 ="SELECT * FROM venuemembers WHERE `UserID` = ".$user_id_2." AND `VenueID` = '".$category_id."' ";
		$get_user_chat_data_2 = mysqli_query($con,$get_user_chat_query_2);
		if($get_user_chat_result_2 = mysqli_fetch_row($get_user_chat_data_2)){
			$previous_chat_time_2 = $get_user_chat_result_2[6];
			$already_added_time_2 = $get_user_chat_result_2[8];
		}
		
		if($already_added_time_2 == 0){
		
			$final_chattime_user_2 = (int) $final_chattime + (int) $previous_chat_time_2;
		
			$query_2 = "UPDATE `venuemembers` SET `ConnectionTime`='".$final_chattime_user_2."',`already_added_connection_time`=1 WHERE `UserID` = '".$user_id_2."' AND `VenueID` = '".$category_id."'";
			mysqli_query($con, $query_2); 
		
		}else{
		
			$query_2 = "UPDATE `venuemembers` SET ConnectionTime = ConnectionTime + 3 WHERE `UserID` = '".$user_id_2."' AND `VenueID` = '".$category_id."'";
			mysqli_query($con, $query_2); 
			
		}
		
		
	}
	else
	{
		
		
		 $Last_Connection = strtotime('now');
		 
		//If no record found start recording
		$stats_query = "INSERT INTO `stats`(`participants`, `chattime`, `room`,`roomId`,`user1`,`user2`) VALUES ('$participants','$chattime','$room','$roomId','$user1','$user2')";
		mysqli_query($con,$stats_query);
		
		$check_venue_member_1 = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id_1." AND `VenueID` = '".$category_id."' ";

		$check_venue_member_data_1 = mysqli_query($con, $check_venue_member_1);
		$check_venue_member_row_1 = mysqli_fetch_row($check_venue_member_data_1);
			
		if($check_venue_member_row_1){ 
			$current_connection_1 = $check_venue_member_row_1[5];
			$total_connection_1 = (int) $current_connection_1 + 1;
			$query_1 = "UPDATE `venuemembers` SET `TotalConnections`='".$total_connection_1."',`LastConnection`='".$Last_Connection."' WHERE `UserID` = '".$user_id_1."' AND `VenueID` = '".$category_id."'";
			mysqli_query($con, $query_1); 
		 }

		$check_venue_member_2 = "SELECT * FROM venuemembers WHERE `UserID` = ".$user_id_2." AND `VenueID` = '".$category_id."' ";
       
		$check_venue_member_data_2 = mysqli_query($con, $check_venue_member_2);
		$check_venue_member_row_2 = mysqli_fetch_row($check_venue_member_data_2);
			
		if($check_venue_member_row_2){ 
			$current_connection_2 = $check_venue_member_row_2[5];
			$total_connection_2 = (int) $current_connection_2 + 1;
			$query_2 = "UPDATE `venuemembers` SET `TotalConnections`='".$total_connection_2."',`LastConnection`='".$Last_Connection."' WHERE `UserID` = '".$user_id_2."' AND `VenueID` = '".$category_id."'";
			mysqli_query($con, $query_2); 
		}
	}
	
}
else{
     
}
// 
?>