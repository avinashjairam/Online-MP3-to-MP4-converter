

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$link = mysqli_connect("xxxx", "xxxx", "xxxx", "xxxx");
// echo"<!DOCTYPE html>";
// require_once("config.php"); 
// Database::connect();
echo"<!DOCTYPE html>";


//  echo mysqli_connect_error();
 session_start(); 
//$message="";
$allowedTypes = array("mp4","mp3","avi","flv","wav"); 



$directory = "../fileconverter/";

global $theFile;
global $trackFileType;


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
			//exec("/var/www/steelpanwebsite.com/public_html/Uploads/workbench/test.sh 2>&1",$output);
			//shell_exec("/var/www/steelpanwebsite.com/public_html/Uploads/workbench/test.sh");
			exec("cd Uploads/workbench && bash ./hello.sh");

			 	if($trackFileType != "mp4"){
			 	exec("cd Uploads/workbench && find . -type f -name '*.".$trackFileType."' -delete");
			 }


			//print_r($output);  // to see the respond to your command

			//rename($path, $path2);

			echo $path;

		$query = "INSERT INTO `workbench` (`path`) VALUES ('". $path2  ."')";		
		 $result=mysqli_query($link, $query);
		}
		else{
			$error = $_FILES['fileUpload']['error'];
			$message =$uploadError[$error];
		}

		$index++;
		//echo $index;
	}

	echo $message; 
}

if(isset($_POST['chain'])){
	$query3 = "SELECT * FROM `workbench`";
	$result3 = mysqli_query($link, $query3);

	$num_rows = mysqli_num_rows($result3);

	//echo $result3;

	if($num_rows < 2){
		echo "You need another at least 2 tracks to create chain. Upload some more.";
		echo $num_rows;
	}
	else{

		//$chainCommand = "cat *.mp3 > chain.mp3";

		$output = exec(("cd Uploads/workbench && bash ./merge.sh")); 
		
		$query7 = "SELECT * FROM `allChains`"; 
		$result4 = mysqli_query($link, $query7);

		//$query10 = "SELECT LAST_INSERT_ID()";
		//$result = mysqli_query($link, $query10);


	//	$row = mysqli_fetch_array($result4);

		$row = mysqli_num_rows($result4) +1 ; 

		//$chainId = $row['id'] + 1;

		$chainId = $row; 

		//Inserting the new chain url into the database
		$output=exec("cd /var/www/steelpanwebsite.com/public_html/Uploads/workbench && mv chain.mp4 /var/www/steelpanwebsite.com/public_html/Uploads/chains/chain".$chainId.".mp4 2>&1",$output );
		print_r($output);
	//	$path = '/var/www/steelpanwebsite.com/public_html/Uploads/chains'. mysqli_insert_id($link).'.mp4';
		$path = './Uploads/chains/chain'. $chainId .'.mp4';

		//rename('/var/www/steelpanwebsite.com/public_html/Uploads/chains', $path);

	//	cd /var/www/steelpanwebsite.com/public_html/Uploads/workbench && mv chains.mp4 /var/www/steelpanwebsite.com/public_html/Uploads/chains/chain4.mp4


		$query4 = "INSERT INTO `allChains` (`path`) VALUES ('".$path ."')";
		$result = mysqli_query($link, $query4);

		$query9 = "INSERT INTO `allTracks` SELECT * FROM `workbench`";
		$result = mysqli_query($link,$query9);

		$query11 = "UPDATE `allTracks` SET `path` = replace(path, 'workbench', 'allTracks')";
		$result = mysqli_query($link,$query11);


		$query8 = "DELETE FROM `workbench`";
		$result = mysqli_query($link,$query8);

		// //moving the new chain to the chains folder. 
		// $output = shell_exec("cd Uploads/workbench && mv 

		// $query5 = "DELETE * FROM `workbench`";
		// $result = mysqli_query($link,$query5);

		// if($trackFileType){
		 //	$output = shell_exec(("cd /var/www/steelpanwebsite.com/public_html/Uploads/workbench &&  mv *.".$trackFileType."/var/www/steelpanwebsite.com/public_html/Uploads/allTracks"));
		// }

			$output = shell_exec(("cd /var/www/steelpanwebsite.com/public_html/Uploads/workbench && bash ./moveTracks.sh"));

		 print_r($output);



	}


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
    		<input type="file" name="fileToUpload" id="fileToUpload"><br>
    		<input type="submit" value="Upload Image" name="submit">
		</form>
	</div>

	<form action="convert.php" method="post">
		<input type="submit" value="Convert" name="Convert">
	</form>

	<script>
		function showImageUpload(e){
			document.getElementById('imageUpload').style.visibility=e.checked && e.id =='yes' ? 'visible' : 'hidden';			
		}

	</script>	
	

</body>

</html>
