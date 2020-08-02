<?php include('upload.php') ?>
   <?php
    if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Website Title</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Home Page</h2>
			<p>Welcome back, <?=$_SESSION['name']?>!</p>
			<?php
include 'connect.php';           
    
    $sql="SELECT * FROM accounts WHERE id = '".$_SESSION['id']."' ";

if ($result=mysqli_query($con,$sql))
  {
  // Fetch one and one row
  while ($row=mysqli_fetch_row($result))
    {
      $admin = $row[1];
      $password = $row[14];
      $var = $row[4];
      $var1 = $row[16];
      $var2 = $row[17];
    }
  // Free result set
  mysqli_free_result($result);
}


//     echo $var;
        if(($admin != 'rungta' )){
           if($var==NULL) {
                
            ?>
			<form action="info.php" method="post">
                School Name:
			    <input type="text" name="schoolname" required> <br>
			    Student 2 Name:
			    <input type="text" name="s2name" required><br>
			    Student 2 mobile:
			    <input type="text" name="s2mobile" required><br>
			    Student 2 email:
			    <input type="text" name="s2email" required><br>
			    Student 3 Name:
			    <input type="text" name="s3name" required><br>
			    Student 3 mobile:
			    <input type="text" name="s3mobile" required><br>
			    Student 3 email:
			    <input type="text" name="s3email" required><br>
			    Mentor Name
			    <input type="text" name="mname" required><br>
			    Mentor Mobile:
			    <input type="text" name="mmobile" required><br>
			    Mentor Email:
			    <input type="text" name="memail" required><br>
			    <input type="submit">
			</form>
			<?php }  
            
            if($var1==NULL){ ?>
                <form action="home.php" method="post" enctype="multipart/form-data">
                  	<?php include('errors.php'); ?>

     School Permission Letter:
    <input type="file" name="file">
    <input type="text" value="one" name="docx" hidden>
    <input type="submit" name="submit" value="Upload">
</form> <?php }
            if($var2==NULL){?>

       <form action="home.php" method="post" enctype="multipart/form-data">
         	<?php include('errors.php'); ?>

    Innovation Presentation / Document:
    <input type="file" name="file">
    <input type="text" value="two" name="docx" hidden>
    <input type="submit" name="submit" value="Upload">
</form> <?php } }
                
                $table = 'accounts';
//                echo $var;
        if($admin== "rungta"){ 
            
         $result = mysqli_query($con,"SELECT * FROM {$table}");
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysqli_num_fields($result);

echo "<h1>Table: {$table}</h1>";
echo "<table border='1'><tr>";
// printing table headers
for($i=0; $i<$fields_num; $i++)
{
//    echo $i;
    $field = mysqli_fetch_field($result);
    if($i==0 || $i==1 || $i==16 || $i==17){
    echo "<td>{$field->name}</td>";}
}
echo "</tr>\n";
// printing table rows
while($row = mysqli_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
//    foreach($row as $cell)
//        echo "<td>$cell</td>";
    echo "<td>$row[0]</td> <td>$row[1]</td>
    <td> <a href='uploads/$row[16]'>$row[16]</a></td> 
    <td> <a href='uploads/$row[17]'>$row[17]</a></td> ";
    echo "</tr>\n";
}
mysqli_free_result($result);   
        }else{
            
            
            
                        
         $result = mysqli_query($con,"SELECT * FROM accounts WHERE id = '".$_SESSION['id']."'");
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysqli_num_fields($result);

echo "<h1>Table: {$table}</h1>";
echo "<table border='1'><tr>";
// printing table headers
for($i=0; $i<$fields_num; $i++)
{
//    echo $i;
    $field = mysqli_fetch_field($result);
    if($i!=14){
    echo "<td>{$field->name}</td>";}
}
echo "</tr>\n";
// printing table rows
while($row = mysqli_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
//    foreach($row as $cell)
//        echo "<td>$cell</td>";
    for($i=0;$i<=17;$i++){
        if($i!=14){
            if($i!=16 && $i!=17){
                echo "<td>$row[$i]</td>";
                echo " $i ";
            }
            else{     
             echo "<td> <a href='uploads/$row[$i]'>$row[$i]</a></td> ";
            }
        }
    }
//    echo "<td>$row[0]</td> <td>$row[1]</td>
//    <td> <a href='uploads/$row[16]'>$row[16]</a></td> 
//    <td> <a href='uploads/$row[17]'>$row[17]</a></td> ";
//    echo "</tr>\n";
}
mysqli_free_result($result);   
            mysqli_close($con);
            
            
        }?>
       
        
      
        
    
      
        
          
  
        
        
        </div>
	</body>
</html>