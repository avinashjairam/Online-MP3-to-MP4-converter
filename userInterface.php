<script>
var downloadLink="";
var download=1;
var imageUploaded=1;
var trackUploaded=1; 
var convertPressed=1;
var duplicateValue=1; 
var overSizedTrack=1;

//localStorage.setItem("trackUploaded", trackUploaded);


</script>


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
    echo "Convert Pressed";
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
                            <label class="white">Select Track to upload:</label><br>
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

                        <label class="white">Would you like to add your own image to the mp4?</label>
                
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
                           <label class="control-label white">Select Image</label>
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

    <!-- <button onclick="hideMainContent();">click</button> -->
     <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>

    <script>



 var downloadContent = '<div align="center" class="embed-responsive embed-responsive-16by9">\
                            <video autoplay loop class="embed-responsive-item">\
                                 <source src='+downloadLink+'.mp4' +'type=video/mp4>\
                            </video> \
                            </div> ';

    
 $(window).load(function() {
        // $('#loading').hide();
        document.getElementById('myVideo').style.display='none';
        document.getElementById('downloadButton').style.display='none';
        document.getElementById('trackUploadSuccess').style.display='none';
        document.getElementById('imageUploadSuccess').style.display='none';
        document.getElementById('imageOption').style.display='none';
        document.getElementById('warning').style.display='none';
        document.getElementById('warningDuplicate').style.display='none';
        document.getElementById('warningLargeFile').style.display='none';
        document.getElementById('warningImage').style.display='none';

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
       alert("image = " + image + " track " + track); 

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
          alert(document.getElementById('imageUpload').style.display);
           showImageWarning();
            return false;
        }
        else{
            alert("hi");
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

       alert("ImageUpload (IU) is " + IU);         
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





  


    $('#file-fr').fileinput({
        language: 'fr',
        uploadUrl: '#',
        allowedFileExtensions : ['jpg', 'png','gif'],
    });
    $('#file-es').fileinput({
        language: 'es',
        uploadUrl: '#',
        allowedFileExtensions : ['jpg', 'png','gif'],
    });
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