<?php 
@$spaceId = @$_SESSION['spaceId'];
$querymembers = "SELECT members FROM category WHERE spaceId='".$spaceId."' AND Name = '".$category."'";
$resultmembers = mysqli_query($con,$querymembers);
$rowmembers = mysqli_fetch_row($resultmembers);
$members = explode(',', $rowmembers[0]);
$count = COUNT($members)-1;
if (in_array($id,$members, TRUE))
{
}
else{
	$membersstr = implode(",",$members);
	$membersup = $membersstr."".$id.",";
	$querymemupdate = "UPDATE category SET members = '".$membersup."' WHERE spaceId='".$spaceId."' AND Name = '".$category."'";
	mysqli_query($con,$querymemupdate);
	$count = $count + 1;
}
$querymemupdate = "UPDATE category SET totalusers = '".$count."' WHERE spaceId='".$spaceId."' AND Name = '".$category."'";
mysqli_query($con,$querymemupdate);
?>