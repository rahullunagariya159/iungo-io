 <?php
// Database configuration
ini_set('session.cookie_domain', '.iungo.io' );
session_start();
include 'connect.php';
$statusMsg = '';
if(isset($_POST['submit'])){
    $var = $_POST['docx'];
// File upload path
$targetDir = "uploads/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('pdf','doc','docx','ppt','pptx','wps','txt');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
//            $insert = $con->query("INSERT into images (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
            if($var=="one"){
             $insert = $con->query("UPDATE accounts SET `file_name`='".$fileName."' WHERE id = '".$_SESSION['id']."'" );
             $var1 =$fileName; }
            else{
             $insert = $con->query("UPDATE accounts SET `file_name2`='".$fileName."' WHERE id = '".$_SESSION['id']."'" );
            }
            
            if($insert){
                $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            }else{
                $statusMsg = "File upload failed, please try again.";
            } 
        }else{
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    }else{
//        $statusMsg = 'Sorry, only pdf, doc, docx, ppt, pptx, wps and txt files are allowed to upload.';
        array_push($errors, "Sorry, only pdf, doc, docx, ppt, pptx, wps and txt files are allowed to upload.");
    }
}else{
    $statusMsg = 'Please select a file to upload.';
}
}
// Display status message
//array_push($errors, $statusMsg);
//           if(empty($_GET['status'])){
//     header('Location:home.php?status=1');
//     exit;
//}
            
?>