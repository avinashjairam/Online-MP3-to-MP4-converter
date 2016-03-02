<!-- Javascript code which resets certain variables each time the page is loaded
The values of these variables determine which divs will be displayed 
By default, 1 represents false and 0 represents true. E.g. if 'download=1', it means that there isn't 
a file to download and the modal which displays the download link remains hidden. The PHP code has variables of the same name.
When the php variables (of the same name ) are changed, so do the javascript variables.-->


<script type="text/javascript">
    var downloadLink="";
    var download=1;
    var imageUploaded=1;
    var trackUploaded=1; 
    var convertPressed=1;
    var duplicateValue=1; 
    var overSizedTrack=1;
</script>

<!-- PHP code  -->
<?php

//Starting session
 session_start(); 

 global $sessionId;
 global $theFile;       //the uploaded file name
 global $trackFileType;
 global $fileWithoutExtension;
 global $tempName;
 global $changeDirectory; //This stores the change directory linux command

 //Same variables named in the above javascript
 global $download;
 global $imageUploaded;
 global $trackUploaded;
 global $ipAddress;   //IP Address 
 global $duplicateValue;
 global $overSizedTrack;
 global $message;

//Initializing variables as false 
 $download=1;
 $imageUploaded=1;
 $trackUploaded=1;
 $convertPressed=1;
 $duplicateValue=1;
 $overSizedTrack=1;
 $result="";
 
 //Storing the IP Address of the user
 $ipAddress= $_SERVER['REMOTE_ADDR'];

//Setting the session ID
 $sessionId = session_id();
 $_SESSION['id'] ;

//Setting the change directory command to 'cd' into the folder which will be created. This folder is named after the session id
 $changeDirectory = "cd $sessionId && ";

//Insert Database connection


//Setting the make directory command to make a directory after the user's session ID
$makeDirectory = "mkdir $sessionId";        

//Setting the permissions for the directory created
$permission = 0700;


//Setting the upload file times. However, the front end only caters for mp3 files. Also, the check for uploaded mp3 files is done on the fron end.
$allowedTypes = array("mp3","avi","flv","wav"); 

//Setting the file upload path. This path is the folder named after the user's session id
$directory = "../fileconverter/" . $sessionId;


//Uploading Track. 
if(isset($_FILES['fileUpload'])){
    //If the upload directory (which is named after the user's session id) is not created but the session id is set, create the upload directory
    //This is done because the the upload directory is deleted every 30 minutes by a chron job  
    if(!is_dir($sessionId) && isset($_SESSION['id'])){      
        exec($makeDirectory, $permission);
    }

    
    //If the user's session ID is not set, create a directory named after that ID. 
    if(!isset($_SESSION['id'])){
       // exec($makeDirectory, $permission); Why am i creating the directory twice? Have no clue 

        //Insert the user's session ID into the database
        $query = "INSERT INTO `sessionInfo` (`sessionId`) VALUES ('$sessionId')";
        $result=mysqli_query($link, $query);

        //Setting the global variabe 'id' to the user's session id
        $_SESSION['id'] = $sessionId;

        exec($makeDirectory, $permission);
    }
       
    //Setting the trackUploaded Flag to true     
    $trackUploaded=0;
   
    //Setting the tempName, fileName, and type of the file uploaded
    $tempName = $_FILES['fileUpload']['tmp_name'];
    $theFile = $_FILES['fileUpload']['name'];
    $type = $_FILES['fileUpload']['type']; 
     
    //Getting the file name without the extension
    $fileWithoutExtension=substr($theFile,0,-4);
   
    $target_file = $directory ."/". basename($_FILES["fileUpload"]["name"]);
    $trackFileType = pathinfo($target_file,PATHINFO_EXTENSION);


    // Check if file already exists. If it does, the duplicateValue flag is set to true
    //Hence, the PHP script stops and this information is passed to javascript.
    //Message is set but is not really used. 

    if (file_exists($target_file)) {
        $message .= "<br>Sorry, file already exists or you didn't upload a file."; 
        $duplicateValue =0;   

     ?>   
         <script>
            //Passing the duplicate value flag to javascript
            duplicateValue = <?php echo json_encode($duplicateValue); ?>;
            localStorage.setItem("duplicateValue", duplicateValue);         
        </script>
<?php
    }
     // Check if file is oversized. If it does, the overSizedTrack flag is set to true
    //Hence, the PHP script stops and this information is passed to javascript.
    //Message is set but is not really used. 
    if ($_FILES['fileUpload']['size'] > 1000000) {
        $message .="<br>Sorry, your file is too large.";      
        $overSizedTrack=0; 
?>   
     <script>
        //Passing the overSizedTrack flag to javascript
        overSizedTrack = <?php echo json_encode($overSizedTrack); ?>;
        localStorage.setItem("overSizedTrack", overSizedTrack);      
       // alert (localStorage.getItem("overSizedTrack"));
    </script>

<?php
    }

    //Backend Check if a wrong file is uploaded 
    if(!in_array($trackFileType, $allowedTypes)){
        $message .= "<br>Sorry, only audio files are allowed.";
    }

    //If the file is legit and the message variable contains no error message, the track is uploaded and moved to the upload directory
    // and the track uploaded flag is passed to javascript 
    //The name of the file is inserted into the 'filesToConvert' table in the database
    //Also, the filename, together with the ip address of the file is inserted into the 'fileUploaders' table in the databse
    if(!isset($message)){    
        if(move_uploaded_file($tempName, $directory ."/".$theFile)){
          
            $ipAddress= $_SERVER['REMOTE_ADDR'];   
           
            $message="File uploaded successfully";
            
            $query = "INSERT INTO `filesToConvert` (`fileName`) VALUES ('". $theFile  ."')";        
            $result=mysqli_query($link, $query);

            $query = "INSERT INTO `fileUploaders` (`ipAddress`, `trackName`) VALUES ('$ipAddress', '$theFile')";
            $result = mysqli_query($link, $query);
    ?>   
         <script>
            //Passing the trackUploaded flag to JavaScript
            trackUploaded= <?php echo json_encode($trackUploaded); ?>;
            localStorage.setItem("trackUploaded", trackUploaded);
          //  alert (localStorage.getItem("trackUploaded"));
        </script>

<?php          
        }
         else{
            //Getting the file upload error and appending it to the error message
            $error = $_FILES['fileUpload']['error'];
            $message += getFileUploadError($error);
        }        
    }
}


//Uploading image 


if(isset($_FILES['image'])){
    //If the user tries the upload an image the php image flag is set to "yes"
    $image ="yes";

    //Getting information from the file
    $tempName = $_FILES['image']['tmp_name'];
    $theFile = $_FILES['image']['name'];    
    $type = $_FILES['image']['type']; 

    //Get the target file path
   $target_file = $directory ."/". basename($_FILES["image"]["name"]);
 
  //Get the file type
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    //Check if the file is an image
    $check = getimagesize($tempName);
    if($check == false) {
        $message = "File is not an image";
    } 
    

    //Check how big image is     
    if ($_FILES['image']['size'] > 500000) {
        $message .="<br>Sorry, your file is too large.";       
    }
    // Allow certain file formats. Again this is checked only on the front end 
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $message .= "<br>Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        
    }
    
    //IF there is no error message, upload the file, and inserting the file name and ip address of uploader to the database
    if(!isset($message)){
        if(move_uploaded_file($tempName, $directory ."/".$theFile)){
            $path = $directory ."/".$theFile;
            $message="File uploaded successfully";
            $query = "INSERT INTO `mp4Pics` (`pic`) VALUES ('". $theFile  ."')";        
            $result=mysqli_query($link, $query);
            $query =  "INSERT INTO `withImage` (`ifImage`) VALUES (TRUE)";  
            $result=mysqli_query($link, $query);

            //Set the image upload flag to true 
            $imageUploaded=0;

            $query = "INSERT INTO `fileUploaders` (`ipAddress`, `imageName`) VALUES ('$ipAddress', '$theFile')";
            $result = mysqli_query($link, $query);
        }
        else{
            $error = $_FILES['image']['error'];
            $message = getFileUploadError($error);
        }
    }  
}

?>
     <script>
        //Passing the imageUploaded flag to JavaScript
            imageUploaded= <?php echo json_encode($imageUploaded); ?>;
            localStorage.setItem("imageUploaded", imageUploaded);
        </script>


<?php


//If the user clicks convert, set the trackUploaded flag to true    
if(isset($_POST['convert'])){

?>

 <script>     
    //Passing the track uploaded flag to javascript
    localStorage.setItem("trackUploaded", trackUploaded);
 </script>





<?php
    //If no track is uploaded, exit the program 
    if(isset($_GET['TU']) == 1 ){
         exit();
    } 
   
    $convertPressed =0;
    $query = "SELECT * FROM `filesToConvert` WHERE `id` = (SELECT MAX(ID) FROM `filesToConvert`)";  
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);

    $theFile=$row['fileName'];
    $fileWithoutExtension=substr($theFile,0,-4);

    /*LEFT


    OFF 



    HERE

    */
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


<!DOCTYPE html>
<!-- release v4.2.8, copyright 2014 - 2015 Kartik Visweswaran -->
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <title>Convert to MP4</title>

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <link href="./css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
        <link href="./css/stylesheet1.css" media="all" rel="stylesheet" type="text/css" />

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   

        <script src="./js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
        <script src="./js/fileinput.min.js" type="text/javascript"></script>


        <script src="./js/fileinput.js" type="text/javascript"></script>
        <script src="./js/fileinput_locale_fr.js" type="text/javascript"></script>
        <script src="./js/fileinput_locale_es.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js" type="text/javascript"></script>
    


        
     


    </head>
<body>



    <div class="navbar navbar-default navbar-fixed-top ">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a  class="navbar-brand" href="#"><img src="./img/logo.JPG"</a>
        </div>
        <div class="collapse navbar-collapse pushDown nav-pills">
          <ul class="nav navbar-nav">
             <li class="active"><a href="http://45.79.163.144/fileconverter/userInterface.php">Home</a></li>
            <li><a href="./about.php">About</a></li>
            <li><a href="./contact.php">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
   </div>
 

    <div id="">
      <!--  <div id="loading" class="row">
        <div  class="col-sm-offset-5 col-sm-2 text-center">
          <h3>Converting...</h3>
          <img id="loading-image" src="./img/loading.gif" alt="Loading..." />
        </div>
       </div> -->

     <!--   <div class="container contentContainer">
            <div class= "row">
                <div id = "download">
                </div>
            </div>
       </div> -->
           
        <div class="container contentContainer">            
                <div class = "row">
                                       
                      <!--   <a href="" id ="downloadButton" class="btn btn-lg btn-success" download>Download My Converted MP4</a> -->
                      <a href="" id ="downloadButton" class="btn btn-lg btn-success" download><span class="glyphicon glyphicon-download-alt"></span> Click Here to Download Your Converted MP4</a>
                       <div class="col-md-6 col-md-offset-3" id="download">  
                          <br><br>
                        <video id="myVideo" controls autoplay>
                          <source id="mp4_src" src="" type="video/mp4">
                          <source id="ogg_src" src="" type="video/ogg">
                          Your browser does not support HTML5 video.
                        </video>

                      
                        <!-- <a href="#" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-arrow-down"></span> Download My Converted MP4 </a> -->
                        <!-- <a href="" id ="downloadButton" class="btn btn-lg btn-success" download><span class="glyphicon glyphicon-arrow-down"></span> Download My Converted MP4 </a> -->

                    </div>
                </div>
       
             <div id="fileUpload" >         
                <div class="row " >            
                    <!-- <form method="post" action="userInterface.php">
                       <label class="control-label">Select Audio File</label>
                      <input id="input-7" name="fileUpload" multiple type="file" class="file file-loading text-center" data-allowed-file-extensions='["mp3", "wav", "m4a"]'> 
                    </form> -->
                    <div id="uploadTrack">
                        <br><br><br>
                        <form action="userInterface.php" method="post" enctype="multipart/form-data" >
                            <label >Select Track to upload:</label><br>
                            <input type="file" name="fileUpload" class="file" id="fileToUpload" data-allowed-file-extensions='["mp3"]'><br>
                      <!--       <input type="submit" value="Upload Track" name="submit"> -->
                        </form>
                    </div>
                </div>
                <div class="row">
                    <!-- <div class="col-md-2 col-md-offset-1" id="trackUploadSuccess"> -->
                     <div class="col-sm-12" id="trackUploadSuccess">
                       <!--  <img src="./img/successful-track-upload.JPG" alt="trackUploadSuccess"/> -->
                        <!--  <a href="" id ="" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-download-alt"></span> Track Uploaded Successfully</a> -->
                        <a href="#" class="btn btn-block btn-success"><span class="glyphicon glyphicon-ok"></span>  File Uploaded Successfully</a>
                         <br>

                    </div>
                </div>

                    

                    <div id="imageOption">

                        <label >Would you like to add your own image to the mp4?</label>
                
                        <form action="userInterface.php" method="post">
                          <input id="no" type="radio" name="image" value="no" checked onchange="showImageUpload(this)"> No
                      
                          <input id="yes" type="radio" name="image" value="yes" onchange="showImageUpload(this)"> Yes
                        </form>

                        <br><br>

                    </div>
                <div class="row">
                   <!--  <div class="col-md-2 col-md-offset-5" id="imageUploadSuccess"> -->
                    <div class="" id="imageUploadSuccess">
                        <!-- <img src="./img/successful-image-upload.JPG" alt="ImageUploadSuccess"/> -->
                        <!-- <a href="" id ="" class="btn btn-lg btn-success" download><span class="glyphicon glyphicon-download-alt"></span> Image Uploaded Successfully</a> -->
                        <a href="#" class="btn btn-block btn-success"><span class="glyphicon glyphicon-ok"></span> Image Uploaded Successfully </a>
                        <br>
                    </div>
                </div>

                   <div id="imageUpload" style="display:none">
                       <form method="post" action="userInterface.php" enctype="multipart/form-data" >
                           <label class="control-label ">Select Image</label>
                           <input  type="file" name="image" class="file" data-allowed-file-extensions='["png", "gif", "jpg", "jpeg"]' >
                        </form>
                    </div>

                     <br>
                     
                </div>

                 <div id ="warning" class="alert alert-danger">
                    <strong>Warning!</strong> Please Upload a Track First! 
                 </div>

                  <div id ="warningImage" class="alert alert-danger">
                    <strong>Warning!</strong> Please Upload an Image! If you don't wish to add an image, please check 'no.' Be aware that if you select this option a default image will be added to the video background.
                 </div>

                  <div id ="warningDuplicate" class="alert alert-danger">
                    <strong>Warning!</strong> You uploaded that file already. Please upload a different file! 
                 </div>

                 <div id ="warningLargeFile" class="alert alert-danger">
                    <strong>Warning!</strong> The file you uploaded exceeds our limit of 1GB. Please upload a smaller file! 
                 </div>

                <br><br>
                
                    <div class="col-sm-offset-5 col-sm-2 text-center">
                        <form method ="post" action="userInterface.php" onsubmit="return checkUpload()">
                           <!--  <a href="#" class=""><span class=""></span> Convert!</a> -->
                            <input type="submit" name = "convert" class="btn btn-block btn-lg btn-primary glyphicon glyphicon-wrench" id ="convert" value="Convert!" /> 
                        </form>
                    </div>
                 
            </div>

        </div>

        <div>
            <footer class="footer">
              <div class="footerStyle">
                <a href="./termsAndConditions.html">Terms and Conditions</a>
              </div>
            </footer>
        </div>

    </div>



    <script  type="text/javascript">

    
 $(document).ready(function() {
        //$('#loading').hide();

        document.getElementById('myVideo').style.display='none';
        document.getElementById('downloadButton').style.display='none';
        document.getElementById('trackUploadSuccess').style.display='none';
        document.getElementById('imageUploadSuccess').style.display='none';
        document.getElementById('imageOption').style.display='none';
        document.getElementById('warning').style.display='none';
        document.getElementById('warningDuplicate').style.display='none';
        document.getElementById('warningLargeFile').style.display='none';
        document.getElementById('warningImage').style.display='none';

   

        // $("#myVideo").hide();
        // $("#downloadButton").hide();
        // $("#trackUploadSuccess").hide();
        // $("#imageUploadSuccess").hide();
        // $("#warning").hide();
        // $("#imageOption").hide();
        // $("#warningImage").hide();
        // $("#warningDuplicate").hide();
        // $("#warningLargeFile").hide();
        // $("#warningImage").hide();


        //TU means 'track uploaded'
        var TU = localStorage.getItem("trackUploaded");

        //IU means 'image uploaded'
     //   var IU = localStorage.getItem("imageUploaded");

        //alert(TU);

        // if(TU==1){
        //     alert('Please upload a track first!');

        // }

        // if(trackUploaded==1 && imageUploaded == 1 && convertPressed == 0){
        //     alert('Please upload a track first!');
        // }

         if(trackUploaded==0){
            hideUploadTrack();
            document.getElementById('imageOption').style.display='block';
        }

        if(imageUploaded==0){
            hideUploadTrack();
            hideUploadImage();
        }

        if(duplicateValue==0){
            document.getElementById("warningDuplicate").style.display='block';
        }

        if(overSizedTrack==0){
            document.getElementById("overSizedTrack").style.display='block';
        }

        //if(larg)

        if(download==0){
            // hideMainContent();
             var vid = document.getElementById("myVideo");
             var downloadButton = document.getElementById("downloadButton");
             var extension = ".mp4";
            hideFileUploadContent();
            var link=  downloadLink.concat(extension);
            vid.src=link;
            downloadButton.href=link;
            //document.write(downloadLink);
           // alert(downloadLink);
            // //document.getElementById('download').innerHtml= downloadContent;
            // document.querySelector("#myVideo > source").src = downloadLink;

            // var video = document.getElementById('video');
            // var source = document.createElement('source');

            // source.setAttribute('src', downloadLink);

            // video.appendChild(source);
          //  video.load();



            document.getElementById('myVideo').style.display='block';
            document.getElementById('downloadButton').style.display='block';


        }

       



     });

    function checkUpload(){
        var track=checkTrackUpload();
       var image;
        //alert(track);
     //  alert("image = " + image + " track " + track); 

       if(track===true && IU == 'block'){
         image=checkImageUpload();
         return false;
       }

       if(track==true && IU == 'none'){
         return true;
       }

       //if (typeof image == 'undefined')

        return false;

        // if (track){
        //     return true;
        // }
        // else{
        //     return false; 
        // }
    }


    function checkTrackUpload(){
          
    if(localStorage.getItem("trackUploaded") == 1 || localStorage.getItem("trackUploaded") === null ) {

       // alert("track can't upload " + localStorage.getItem("trackUploaded") );
       document.getElementById('warning').style.display='block';
        return false;
     }
     else{
        return true;
     }
    }

    function checkImageUpload(){
        if(document.getElementById('imageUpload').style.display='block' && (localStorage.getItem("imageUploaded" )== 1 || localStorage.getItem("imageUploaded") == null) ){
          //alert(localStorage.getItem("imageUploaded"));
        //  alert(document.getElementById('imageUpload').style.display);
           showImageWarning();
            return false;
        }
        else{
           // alert("hi");
            hideImageWarning();
            return true;
        }



    }

    function showImageWarning(){
        document.getElementById('warningImage').style.display='block';
    }

    function hideImageWarning(){
        document.getElementById('warningImage').style.display='none';
    }

    function hideUploadTrack(){
        // document.getElementById("trackUploadSuccess").style.display="none";
     //    var elem1 = document.createElement("img");
     //    elem1.setAttribute("src", "./img/successful-track-upload.JPG");     
     //    elem1.setAttribute("alt", "Track uploaded successfully");
     //    document.getElementById("uploadTrack").innerHtml='';
     // document.getElementById("uploadTrack").appendChild(elem1);
     document.getElementById("uploadTrack").style.display="none";
     document.getElementById("trackUploadSuccess").style.display="block";
    }

    function hideUploadImage(){
        // document.getElementById("imageOption").style.display="none";
        //  var elem2 = document.createElement("img");
        // elem2.setAttribute("src", "./img/successful-image-upload.JPG");     
        // elem2.setAttribute("alt", "Image uploaded successfully");
        // document.getElementById("imageOption").innerHtml='';
        // document.getElementById("imageOption").appendChild(elem2);
        // document.getElementById("imageOption").style.display="block";
        document.getElementById("uploadTrack").style.display="none";
        document.getElementById("imageOption").style.display="none";
        document.getElementById("trackUploadSuccess").style.display="block"; 
        document.getElementById("imageUploadSuccess").style.display="block";
    }




                            


    var myEl = document.getElementById('convert');

   

    function showImageUpload(e){
        document.getElementById('imageUpload').style.display=e.checked && e.id =='yes' ? 'block' : 'none';  

        //IU = localStorage.setItem(document.getElementById('imageUpload').style.display);
       // alert(document.getElementById('imageUpload').style.display);

       IU=document.getElementById('imageUpload').style.display;

       if(IU=='none'){
        hideImageWarning();
       }

     //  alert("ImageUpload (IU) is " + IU);         
    }

    function hideFileUploadContent(){
        document.getElementById('fileUpload').style.visibility='hidden';
    }
   

    $(document).on('ready', function() {
        $("#input-21").fileinput({
            previewFileType: "image",
            browseClass: "btn btn-primary",
            browseLabel: "Pick Image",
            browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
            removeClass: "btn btn-danger",
            removeLabel: "Delete",
            removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
            uploadClass: "btn btn-info",
            uploadLabel: "Upload",
            uploadUrl: "./userInterface.php",
            uploadIcon: "<i class=\"glyphicon glyphicon-upload\"></i> "

        });
    });





  


    // $('#file-fr').fileinput({
    //     language: 'fr',
    //     uploadUrl: '#',
    //     allowedFileExtensions : ['jpg', 'png','gif'],
    // });
    // $('#file-es').fileinput({
    //     language: 'es',
    //     uploadUrl: '#',
    //     allowedFileExtensions : ['jpg', 'png','gif'],
    // });
    $("#file-0").fileinput({
        'allowedFileExtensions' : ['jpg', 'png','gif'],
    });
    $("#file-1").fileinput({
        uploadUrl: '#', // you must set a valid URL here else you will get an error
        allowedFileExtensions : ['jpg', 'png','gif'],
        overwriteInitial: false,
        maxFileSize: 1000,
        maxFilesNum: 10,
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function(filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });
    /*
    $(".file").on('fileselect', function(event, n, l) {
        alert('File Selected. Name: ' + l + ', Num: ' + n);
    });
    */
    $("#file-3").fileinput({
        showUpload: false,
        showCaption: false,
        browseClass: "btn btn-primary btn-lg",
        fileType: "any",
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
    });

    $("#file-4").fileinput({
        uploadExtraData: {kvId: '10'}
    });

    $(".btn-warning").on('click', function() {
        if ($('#file-4').attr('disabled')) {
            $('#file-4').fileinput('enable');
        } else {
            $('#file-4').fileinput('disable');
        }
    });    
    
    $(".btn-info").on('click', function() {
        $('#file-4').fileinput('refresh', {previewClass:'bg-info'});
    });
    /*
    $('#file-4').on('fileselectnone', function() {
        alert('Huh! You selected no files.');
    });
    $('#file-4').on('filebrowse', function() {
        alert('File browse clicked for #file-4');
    });
    */
    $(document).ready(function() {
        $("#test-upload").fileinput({
            'showPreview' : false,
            'allowedFileExtensions' : ['jpg', 'png','gif'],
            'elErrorContainer': '#errorBlock'
        });
        /*
        $("#test-upload").on('fileloaded', function(event, file, previewId, index) {
            alert('i = ' + index + ', id = ' + previewId + ', file = ' + file.name);
        });
        */
    });
  












    </script>
    </body>
</html>