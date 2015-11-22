

<?php
echo"<!DOCTYPE html>";


 echo mysqli_connect_error();
 session_start(); 


$link = mysqli_connect("localhost", "avi", "avi","cl55-steel");

// $directory = "./Uploads";
$directory = "../fileconverter/";
global $theFile;

if(isset($_POST['submit'])){
//	echo $user->getLink();
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

	$tempName = $_FILES['fileUpload']['tmp_name'];
	$theFile = $_FILES['fileUpload']['name'];
	
	$type = $_FILES['fileUpload']['type']; 

	// $target_file = $_SERVER['DOCUMENT_ROOT']. $directory . basename($theFile);
	$target_file = $directory ."/". basename($_FILES["fileUpload"]["name"]);
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
	if ($_FILES['fileUpload']['size'] > 500000) {
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
			$error = $_FILES['fileUpload']['error'];
			$message =$uploadError[$error];
		}
	}

	echo $message; 
}



?>

<html>

<head>
<title>File Upload Image</title>
</head>

<body>
	<form action="uploadImage.php" method="post" enctype="multipart/form-data">
    	Select Track to upload:<br>
	    <input type="file" name="fileUpload" id="fileToUpload"><br>
	    <input type="submit" value="Upload Image" name="submit">
	</form>


	
	
	

</body>

</html>
