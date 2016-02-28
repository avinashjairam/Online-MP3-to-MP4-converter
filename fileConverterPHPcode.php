

<?php
 session_start(); 

 global $sessionId;
 global $theFile;
 global $trackFileType;
 global $fileWithoutExtension;
 global $tempName;
 global $changeDirectory; 
 global $download;
 global $imageUploaded;
 global $trackUploaded;
 global $ipAddress;   //IP Address 
 global $duplicateValue;
 global $overSizedTrack;
 //global $HTTP_X_FORWARDED_FOR;

 $download=1;
 $imageUploaded=1;
 $trackUploaded=1;
 $convertPressed=1;
 $duplicateValue=1;
 $overSizedTrack=1;
 $result="";
 
 global $message;

 $ipAddress= $_SERVER['REMOTE_ADDR'];

$inactive = 10;

$sessionLife = time() - $_SESSION['timeout'];
//echo $sessionLife."<br>";

if($sessionLife > $inactive){
    //echo"<script>alert('session timeout');</script>";
}

$_SESSION['timeout'] = time();


//echo $_session['timeout'];

 $sessionId = session_id();
 $_SESSION['id'] ;

//echo $_SESSION['id'];

 //echo $sessionId;
 $changeDirectory = "cd $sessionId && ";

// echo $sessionId;
//echo $output;

ini_set('display_errors',1);
error_reporting(E_ALL);
//Insert Database connection
$link = mysqli_connect("localhost", "avi", "avi","cl55-steel");


 $makeDirectory = "mkdir $sessionId";        
$permission = 0700;


$allowedTypes = array("mp3","avi","flv","wav"); 
$directory = "../fileconverter/" . $sessionId;
//echo '<h3>hi</h3>';


if(isset($_FILES['fileUpload'])){
    if(!is_dir($sessionId) && isset($_SESSION['id'])){
        echo "creating directory";
        //$makeDirectory = "mkdir $sessionId";        
        //$permission = 0700;
        exec($makeDirectory, $permission);//$_SESSION['id']

    }

    
        //echo ;
    if(!isset($_SESSION['id'])){
        exec($makeDirectory, $permission);//$_SESSION['id']
        $query = "INSERT INTO `sessionInfo` (`sessionId`) VALUES ('$sessionId')";
        //echo $query;
        $result=mysqli_query($link, $query);

        $_SESSION['id'] = $sessionId;

        exec($makeDirectory, $permission);
    }

            //echo ("{$_SESSION['id']}");
    $trackUploaded=0;

    //echo $result;

//  echo $user->getLink();
   // echo '<h3>hi</h3>';
    $tempName = $_FILES['fileUpload']['tmp_name'];
    $theFile = $_FILES['fileUpload']['name'];
    
   // echo "The name of the file is ".$theFile."'<br>'";
    $fileWithoutExtension=substr($theFile,0,-4);
    $type = $_FILES['fileUpload']['type']; 
//  echo "type is ". $type."<br>";
    // $target_file = $_SERVER['DOCUMENT_ROOT']. $directory . basename($theFile);
    $target_file = $directory ."/". basename($_FILES["fileUpload"]["name"]);
    $trackFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    //echo "trackfiletype ". $trackFileType."<br>";
    // $check = getimagesize($tempName);
    // if($check == false) {
 //        $message = "File is not an image";
 //    } 

    // Check if file already exists

    if (file_exists($target_file)) {
        $message .= "<br>Sorry, file already exists or you didn't upload a file."; 
        $duplicateValue =0;   

     ?>   
         <script>
            duplicateValue = <?php echo json_encode($duplicateValue); ?>;
            localStorage.setItem("duplicateValue", duplicateValue);
           // window.location.href = "http://45.79.163.144/fileconverter/userInterface.php?TU=" + localStorage.getItem("trackUploaded"); 
            alert (localStorage.getItem("duplicateValue"));
        </script>
<?php



    }


    // Check file size
    if ($_FILES['fileUpload']['size'] > 1000000) {
        $message .="<br>Sorry, your file is too large.";      
        $overSizedTrack=0; 

   ?>   
     <script>
        overSizedTrack = <?php echo json_encode($overSizedTrack); ?>;
        localStorage.setItem("overSizedTrack", overSizedTrack);
       // window.location.href = "http://45.79.163.144/fileconverter/userInterface.php?TU=" + localStorage.getItem("trackUploaded"); 
        alert (localStorage.getItem("overSizedTrack"));
    </script>
<?php
    }
    // Allow certain file formats
    // if($trackFileType != "mp4"  or $trackFileType != "mp3" || $trackFileType != "avi" || $trackFileType != "flv" || $trackFileType != "wmv") {
    //     $message .= "<br>Sorry, only audio files are allowed.";
        
    // }
    // print_r(in_array($trackFileType, $allowedTypes)) ;
    // print_r($allowedTypes);
    //echo gettype($trackFileType);
    if(!in_array($trackFileType, $allowedTypes)){
        $message .= "<br>Sorry, only audio files are allowed.";
    }
//  echo "type is ". $type."<br>";
    // if($trackFileType=="mp4")
    //  echo "hello";
    
    if(!isset($message)){
        $index = 1; 
        if(move_uploaded_file($tempName, $directory ."/".$theFile)){
            //$theFile = str_replace($trackFileType, "mp4", $theFile); 

            $ipAddress= $_SERVER['REMOTE_ADDR'];

            $path = $directory ."/".$theFile;
            $path2 = $directory ."/". $theFile;
            //echo $path2;
            $message="File uploaded successfully";
            
            $query = "INSERT INTO `filesToConvert` (`fileName`) VALUES ('". $theFile  ."')";        
            $result=mysqli_query($link, $query);

            $query = "INSERT INTO `fileUploaders` (`ipAddress`, `trackName`) VALUES ('$ipAddress', '$theFile')";
            $result = mysqli_query($link, $query);
            //echo $result;

         ?>   
         <script>
            trackUploaded= <?php echo json_encode($trackUploaded); ?>;
            localStorage.setItem("trackUploaded", trackUploaded);
           // window.location.href = "http://45.79.163.144/fileconverter/userInterface.php?TU=" + localStorage.getItem("trackUploaded"); 

            alert (localStorage.getItem("trackUploaded"));

        </script>
<?php
          
        }
      

      
       



        else{
            $error = $_FILES['fileUpload']['error'];
            $message = getFileUploadError($error);
        }
        
    }
   // echo $message; 
}








if(isset($_FILES['image'])){
//   exec('mkdir $sessionId',$output,$result);
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
    // if (file_exists($target_file)) {
    //     $message .= "<br>Sorry, file already exists.";    
    // }
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

            $imageUploaded=0;


            $query = "INSERT INTO `fileUploaders` (`ipAddress`, `imageName`) VALUES ('$ipAddress', '$theFile')";
            $result = mysqli_query($link, $query);
        }
        else{
            $error = $_FILES['image']['error'];
            $message =getFileUploadError($error);
        }
    }
  //  echo $message; 
}



?>
     <script>
            imageUploaded= <?php echo json_encode($imageUploaded); ?>;
            localStorage.setItem("imageUploaded", imageUploaded);

        </script>

<?php
    
if(isset($_POST['convert'])){

?>

 <script>
          //  imageUploaded= <?php echo json_encode($imageUploaded); ?>;
    
    localStorage.setItem("trackUploaded", trackUploaded);


 </script>





<?php
    if(isset($_GET['TU']) == 1 ){
        echo "<script> noTrackUploaded(); </script>";
        exit();
    } 
   // echo "Convert Pressed";
    $convertPressed =0;
   // echo "The name of the file is ".$theFile."<br>";
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
    $num_rows    = mysqli_num_rows($imageResult);
    //$go = 0;
    $currentId = $imageRow['id'];
    
    if($num_rows == 0){
        $go = 0;
       // echo "go is ". $go;
    }
    else{
        $go = 1; 
    }
    
    if($go == 0){
        // if(function_exists('exec')) {
        //     echo "exec is enabled";
        // }
        $defaultConversion = "$changeDirectory ffmpeg -loop 1 -i ../image.jpg -i \"" . $theFile."\" -c:v libx264 -c:a aac -strict experimental -b:a 192k -shortest -vf scale=800:400 -pix_fmt yuv420p \"" . $fileWithoutExtension."\".mp4";
    //    echo "no " .$defaultConversion;
        //exec("cd fileconverter && " .$defaultConversion);
        exec($defaultConversion, $output,$return);
    //        echo "<br>Return is ". $return;
      //      echo 'Download';
        if($return==0)
            $download=0;

        ?>

         <script>
             download = <?php echo json_encode($download); ?>;

             downloadLink=<?php echo json_encode($sessionId."/".$fileWithoutExtension); ?>;

             convertPressed = <?php echo json_encode($convertPressed); ?>;
             localStorage.setItem("trackUploaded", 1);
            // alert(downloadLink);


          </script>
 

  <!--     <div class="modal hide fade" id="myModal">
          <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h3>Modal header</h3>
          </div>
          <div class="modal-body">
             <a href=" http://45.79.163.144/fileconverter/<?php echo $sessionId."/".$fileWithoutExtension ?>.mp4" target="_blank" download>Download here</a>"
          </div>
          <div class="modal-footer">
            <a href="#" class="btn">Close</a>
            <a href="#" class="btn btn-primary">Save changes</a>
          </div>
         </div> -->
       

        

            
       
       
            <!-- This link opens in a new tab but the download link doesn't work -->
            <!-- <a href="#" onclick="window.open('http://45.79.163.144/fileconverter/<?php echo $sessionId."/".$fileWithoutExtension ?>.mp4','_blank');window.close();return false" target="_blank" download>Download here</a>"; -->
    


        <?php 
        
    }
    else{
        $imageConversion = "$changeDirectory ffmpeg -loop 1 -i \"". $image ."\" -i \"" . $theFile."\" -c:v libx264 -c:a aac -strict experimental -b:a 192k -shortest -vf scale=800:400 -pix_fmt yuv420p \"" . $fileWithoutExtension."\".mp4";
      //  echo "yes ". $imageConversion;
        //exec("cd fileconverter && " .$defaultConversion);
        exec($imageConversion, $output,$return);
   //     echo " Return is ". $return;
        if($return==0)          
            $download=0;
            ?>
        <!-- <a href=" http://45.79.163.144/fileconverter/<?php echo $sessionId."/".$fileWithoutExtension ?>.mp4" target="_blank" download>Download here</a>"; -->
        <script>
        //     $('#myModal').modal('show');
            download = <?php echo json_encode($download); ?>;
            downloadLink=<?php echo json_encode($sessionId."/".$fileWithoutExtension); ?>;
         </script> 

     
            
        <?php
        $query = "DELETE FROM `withImage` WHERE `id` = ". $currentId ;
      //  echo "<br>".$query;
        mysqli_query($link, $query);
        //$imageRow = mysqli_fetch_array($imageResult);
    }
}
function getFileUploadError($error){
        $uploadError = array( 
            UPLOAD_ERR_OK           => "There is no error.",
            UPLOAD_ERR_INI_SIZE     => "The uploaded file exceeds the upload_max_filesize directive ",
            UPLOAD_ERR_FORM_SIZE    => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
            UPLOAD_ERR_PARTIAL      => "The uploaded file was only partially uploaded.",
            UPLOAD_ERR_NO_FILE      => "No file was uploaded.",
            UPLOAD_ERR_NO_TMP_DIR   => "Missing a temporary folder.",
            UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION    => "A PHP extension stopped the file upload."
        );
        return $uploadError[$error];
}
function session_valid_id($session_id){
    return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $session_id) > 0;
}
function checkAllowedTypes($type){
}
?>