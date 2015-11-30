

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

//Insert Database connection
$link = mysqli_connect("localhost", "avi", "avi","cl55-steel");



echo"<!DOCTYPE html>";

 session_start(); 
//$message="";
$allowedTypes = array("mp4","mp3","avi","flv","wav"); 
$directory = "../fileconverter/";

global $theFile;
global $trackFileType;
global $fileWithoutExtension;
global $tempName;


if(isset($_POST['submit'])){
//	echo $user->getLink();
	
	$tempName = $_FILES['fileUpload']['tmp_name'];
	$theFile = $_FILES['fileUpload']['name'];
	
	echo "The name of the file is ".$theFile."<br>";
	$fileWithoutExtension=substr($theFile,0,-4);

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
			//$theFile = str_replace($trackFileType, "mp4", $theFile); 
			$path = $directory ."/".$theFile;
			$path2 = $directory ."/". $theFile;
			//echo $path2;
			$message="File uploaded successfully";
			
			$query = "INSERT INTO `filesToConvert` (`fileName`) VALUES ('". $theFile  ."')";		
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

	$image ="yes";

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
			$query = "INSERT INTO `mp4Pics` (`pic`) VALUES ('". $theFile  ."')";		
			$result=mysqli_query($link, $query);
			$query =  "INSERT INTO `withImage` (`ifImage`) VALUES (TRUE)";	
			$result=mysqli_query($link, $query);
		}
		else{
			$error = $_FILES['image']['error'];
			$message =getFileUploadError($error);
		}
	}

	echo $message; 
}
	


if(isset($_POST['convert'])){
	echo "Convert Pressed";
	echo "The name of the file is ".$theFile."<br>";

	$query = "SELECT * FROM `filesToConvert` WHERE `id` = (SELECT MAX(ID) FROM `filesToConvert`)";	
	$result=mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);


	$theFile=$row['fileName'];
	$fileWithoutExtension=substr($theFile,0,-4);

	$imageQuery = "SELECT * FROM `mp4Pics` WHERE `id` = (SELECT MAX(ID) FROM `mp4Pics`)";	
	$imageResult= mysqli_query($link, $imageQuery);
	$imageRow = mysqli_fetch_array($imageResult);

	$image = $imageRow['pic'];//HUGE MISTAKE HERE, IMPROPER USE OF VARIABLES 

	$useImage = "SELECT * FROM `withImage` WHERE `id` = (SELECT MAX(ID) FROM `withImage`)";
	$imageResult= mysqli_query($link, $useImage);
	$imageRow = mysqli_fetch_array($imageResult);

	//$doImage = $imageRow['ifImage'];
	//echo $theFile;
	$query = "SELECT * FROM withImage";
	$imageResult = mysqli_query($link,$query);
	$num_rows 	 = mysqli_num_rows($imageResult);
	//$go = 0;
	$currentId = $imageRow['ifImage'];
	
	if($num_rows == 0){
		$go = 0;
		echo "go is ". $go;
	}
	else{
		$go = 1; 
	}
	
	if($go == 0){

		// if(function_exists('exec')) {
		//     echo "exec is enabled";
		// }

		$defaultConversion = "ffmpeg -loop 1 -i image.jpg -i \"" . $theFile."\" -c:v libx264 -c:a aac -strict experimental -b:a 192k -shortest -vf scale=800:400 \"" . $fileWithoutExtension."\".mp4";
		echo "no " .$defaultConversion;
		//exec("cd fileconverter && " .$defaultConversion);
		exec($defaultConversion);
	}
	else{
		$imageConversion = "ffmpeg -loop 1 -i \"". $image ."\" -i \"" . $theFile."\" -c:v libx264 -c:a aac -strict experimental -b:a 192k -shortest -vf scale=800:400 \"" . $fileWithoutExtension."\".mp4";
		echo "yes ". $imageConversion;
		//exec("cd fileconverter && " .$defaultConversion);
		exec($imageConversion);

		$query = "DELETE FROM `withImage` WHERE `id` = `". $currentId ."`";
		echo "<br>".$query;
		mysqli_query($link, $query);
		//$imageRow = mysqli_fetch_array($imageResult);


	}


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
	
	<form action="convert.php" method="post">
	  <input id="no" type="radio" name="imageNo" value="no" checked onchange="showImageUpload(this)"> No
	  <br>
	  <input id="yes" type="radio" name="imageYes" value="yes" onchange="showImageUpload(this)"> Yes
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
