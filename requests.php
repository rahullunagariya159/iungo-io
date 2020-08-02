<?php
if ($_SESSION['name'] == "admin" ){

	// Change this to your connection info.
	include 'connect.php';
	if(isset($_POST['user-id']))
	{	
		$access = 0;
		if(isset($_POST['accept'])) 
		{
			$access = 1;
		}
		if(isset($_POST['reject']))
		{
			$access = 2;
		}
		$id = $_POST['user-id'];
		$query = "UPDATE validate SET access = '".$access."' WHERE id = '".$id."'";
		mysqli_query($con,$query);
	}
	$query = "SELECT * FROM validate WHERE access = 0";
	$result = mysqli_query($con,$query);
	while($row=mysqli_fetch_row($result))
	{
		?>
			<div class="col-9">
				<div class="row">
					<div class="col-4">NAME :-</div>
					<div class="col-4"><?php echo $row[2] ?></div>
					<div class="col-4"></div>
				</div>
				<div class="row">
					<div class="col-4">EMAIL :-</div>
					<div class="col-4"><?php echo $row[5] ?></div>
					<div class="col-4"></div>
				</div>
				<div class="row">
					<div class="col">
						<?php echo $row[3] ?>
					</div>
				</div>
			</div>
			<div class="col-3">
				<form action="" method="post">
					<input type="text" value="<?php echo $row[2] ?>" name="user-id" hidden>
					<div class="row">
						<div class="col"><button name="accept" class="accept btn btn-success">ACCEPT</button></div>
					</div>
					<div class="row">
						<div class="col"><button name="reject" class="reject btn btn-danger">REJECT</button></div>
					</div>
				</form>
			</div>
		<?php
	}
}
else
{
	include 'connect.php';

	if(isset($_POST['user-id']))
	{	

		$username = $_POST['user-validate'];
		$id = $_POST['user-id'];
		$access_members = $_POST['access-members'];
		// echo $access_members."---access---<br>";
		$access_members = explode(",", $access_members);
		$members = $_POST['members'];
		// echo $access_members;
		$category = $_POST['category'];

		// echo "Please Go back to main page and join again";
		$count = COUNT(explode(",",$members))-1;
		if (in_array($id,explode(",",$members), TRUE))
		{
		}
		else{
			// echo "<br> members".implode(",", $members);
			$membersstr = $members;
			// echo $membersstr;
			// echo "hi<br>";
			$membersup = $membersstr."".$id.",";
			$querymemupdate = "UPDATE category SET members = '".$membersup."' WHERE Name = '".$category."'";
			mysqli_query($con,$querymemupdate);
			$count = $count + 1;

			$querymemupdate = "UPDATE category SET totalusers = '".$count."' WHERE Name = '".$category."'";
			mysqli_query($con,$querymemupdate);

			// echo $access_members[0];
			// echo $id,"<br>";
			$index = array_search($id,$access_members);
			if($index !== FALSE){
			    unset($access_members[$index]);
			}
			$access_members = implode(",", $access_members);
			// echo $access_members;
			$query_update = "UPDATE category SET access = '$access_members' WHERE Name = '".$category."' ";
			mysqli_query($con,$query_update);
			// echo "index - ".$index;
		}

	}

	$query_room = "SELECT * FROM category WHERE createdBy = '".$_SESSION['id']."'";
	$result_room = mysqli_query($con,$query_room);
	while($row=mysqli_fetch_array($result_room))
	{
		$category = $row['Name'];
		$members = $row['members'];
		$users = explode(",",$row['access']);
		

		for ($x = 0; $x < COUNT($users)-1; $x++)
		{
			$user = $users[$x];
			
			$user_data = "SELECT * FROM accounts WHERE id = '$user'";
			$user_data_result = mysqli_query($con,$user_data);
			$user_data_row = mysqli_fetch_array($user_data_result);
		

		?>
	
			<div class="col-9">
				<div class="row">
					<div class="col-4">Chatroom :-</div>
					<div class="col-4"><?php echo $category ?></div>
					<div class="col-4"></div>
				</div>
				<div class="row">
					<div class="col-4">NAME :-</div>
					<div class="col-4"><?php echo $user_data_row['firstname'] ?></div>
					<div class="col-4"></div>
				</div>
				<div class="row">
					<div class="col-4">EMAIL :-</div>
					<div class="col-4"><?php echo $user_data_row['email'] ?></div>
					<div class="col-4"></div>
				</div>
			</div>
			<div class="col-3">
				<form action="" method="post">
					<input type="text" value="<?php echo $user_data_row['firstname'] ?>" name="user-validate" hidden>
					<input type="text" value="<?php echo $user_data_row['id'] ?>" name="user-id" hidden>
					<input type="text" value="<?php echo implode(",",$users) ?>" name="access-members" hidden>
					<input type="text" value="<?php echo $members ?>" name="members" hidden>
					<input type="text" value="<?php echo $category ?>" name="category" hidden>
					<div class="row">
						<div class="col"><button name="accept" class="accept btn btn-success">ACCEPT</button></div>
					</div>
					<div class="row">
						<div class="col"><button name="reject" class="reject btn btn-danger">REJECT</button></div>
					</div>
				</form>
			</div>

		<?php
		}

	}





	

}
?>