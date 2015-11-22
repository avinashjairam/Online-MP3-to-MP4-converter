

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
// $link = mysqli_connect("xxxx", "xxxx", "xxxx", "xxxx");



echo"<!DOCTYPE html>";

 session_start(); 
//$message="";
$allowedTypes = array("mp4","mp3","avi","flv","wav"); 
$directory = "../fileconverter/";
global $theFile;
global $trackFileType;
if(isset($_POST['submit'])){
//	echo $user->getLink();
	
	$tempName = $_FILES['fileUpload']['tmp_name'];
	$theFile = $_FILES['fileUpload']['name'];
	
	$type = $_FILES['fileUpload']['type']; 
//	echo "type is ". $type."<br>";
	// $target_file = $_SERVER['DOCUMENT_ROOT']. $directory . basename($theFile);
	$target_file = $directory ."/". basename($_FILES["fileUpload"]["name"]);
	$trackFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	echo "trackfiletype ". $trackFileType."<br>";
	// $check = getimagesize($tempName);
	// if($check == false) {
 //        $message = "File is not an image";
 //    } 
		// Check if file already exists
	if (file_exists($target_file)) {
	    $message .= "<br>Sorry, file already exists or you didn't upload a file.";	  
	}
	// Check file size
	if ($_FILES['fileUpload']['size'] > 1000000) {
	    $message .="<br>Sorry, your file is too large.";	   
	}
	// Allow certain file formats
	// if($trackFileType != "mp4"  or $trackFileType != "mp3" || $trackFileType != "avi" || $trackFileType != "flv" || $trackFileType != "wmv") {
	//     $message .= "<br>Sorry, only audio files are allowed.";
	    
	// }
	// print_r(in_array($trackFileType, $allowedTypes)) ;
	// print_r($allowedTypes);
	echo gettype($trackFileType);
	if(!in_array($trackFileType, $allowedTypes)){
		$message .= "<br>Sorry, only audio files are allowed.";
	}
//	echo "type is ". $type."<br>";
	// if($trackFileType=="mp4")
	// 	echo "hello";
    
	if(!isset($message)){
		$index = 1; 
		if(move_uploaded_file($tempName, $directory ."/".$theFile)){
			$theFile = str_replace($trackFileType, "mp4", $theFile); 
			$path = $directory ."/".$theFile;
			$path2 = $directory ."/". $theFile;
			//echo $path2;
			$message="File uploaded successfully";
			
			$query = "INSERT INTO `workbench` (`path`) VALUES ('". $path2  ."')";		
			 $result=mysqli_query($link, $query);
		}
		else{
			$error = $_FILES['fileUpload']['error'];
			$message = getFileUploadError($error);
		}
		
	}
	echo $message; 
}

if(isset($_POST['Upload'])){
	echo "prining";
	$tempName = $_FILES['image']['tmp_name'];
	$theFile = $_FILES['image']['name'];
	
	$type = $_FILES['image']['type']; 

	// $target_file = $_SERVER['DOCUMENT_ROOT']. $directory . basename($theFile);
	$target_file = $directory ."/". basename($_FILES["image"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$check = getimagesize($tempName);


	if($check == false) {
        $message = "File is not an image";
    } 
		// Check if file already exists
	if (file_exists($target_file)) {
	    $message .= "<br>Sorry, file already exists.";	  
	}
	// Check file size
	if ($_FILES['image']['size'] > 500000) {
	    $message .="<br>Sorry, your file is too large.";	   
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
	    $message .= "<br>Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    
	}

    
	if(!isset($message)){
		if(move_uploaded_file($tempName, $directory ."/".$theFile)){
			$path = $directory ."/".$theFile;
			$message="File uploaded successfully";
		$query = "INSERT INTO `allPics` (`path`) VALUES ('". $path  ."')";		
		 $result=mysqli_query($link, $query);
		}
		else{
			$error = $_FILES['image']['error'];
			$message =getFileUploadError($error);
		}
	}

	echo $message; 
}
	



function getFileUploadError($error){
		$uploadError = array( 
			UPLOAD_ERR_OK 			=> "There is no error.",
			UPLOAD_ERR_INI_SIZE		=> "The uploaded file exceeds the upload_max_filesize directive ",
			UPLOAD_ERR_FORM_SIZE	=> "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
			UPLOAD_ERR_PARTIAL		=> "The uploaded file was only partially uploaded.",
			UPLOAD_ERR_NO_FILE		=> "No file was uploaded.",
			UPLOAD_ERR_NO_TMP_DIR	=> "Missing a temporary folder.",
			UPLOAD_ERR_CANT_WRITE	=> "Failed to write file to disk.",
			UPLOAD_ERR_EXTENSION	=> "A PHP extension stopped the file upload."
		);
		return $uploadError[$error];
	}



function checkAllowedTypes($type){

}

?>


<html>

<head>
<title>MP3 to MP4 Converter</title>
</head>

<body>

	<h3>Convert your mp3 file to a mp4</h3> 

	<h3>Upload your mp3 file</h3>
	<form action="convert.php" method="post" enctype="multipart/form-data">
    	Select Track to upload:<br>
	    <input type="file" name="fileUpload" id="fileToUpload"><br>
	    <input type="submit" value="Upload Track" name="submit">
	</form>
	<br><br>

	<h3>Would you like to add your own image to the mp4?</h3>
	
	<form>
	  <input id="no" type="radio" name="image" value="male" checked onchange="showImageUpload(this)"> No
	  <br>
	  <input id="yes" type="radio" name="image" value="female" onchange="showImageUpload(this)"> Yes
	</form>

	<br><br>

	<div id="imageUpload" style="visibility:hidden">
		<form action="" method="post" enctype="multipart/form-data">
    		Select image to upload:<br>
    		<input type="file" name="image" id="fileToUpload"><br>
    		<input type="submit" value="Upload Image" name="Upload">
		</form>
	</div>

	<form action="convert.php" method="post">
		<input type="submit" value="Convert" name="convert">
	</form>

	<script>
		function showImageUpload(e){
			document.getElementById('imageUpload').style.visibility=e.checked && e.id =='yes' ? 'visible' : 'hidden';			
		}

	</script>	
	

</body>

</html>
