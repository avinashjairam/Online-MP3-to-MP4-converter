<script>
var downloadLink="";
var download=1;

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

 $download=1;

$result="";
global $message;

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

echo $_SESSION['id'];

 //echo $sessionId;
 $changeDirectory = "cd $sessionId && ";

// echo $sessionId;
//echo $output;

ini_set('display_errors',1);
error_reporting(E_ALL);
//Insert Database connection


$allowedTypes = array("mp3","avi","flv","wav"); 
$directory = "../fileconverter/" . $sessionId;
echo '<h3>hi</h3>';


if(isset($_FILES['fileUpload'])){
    $makeDirectory = "mkdir $sessionId";
    $permission = 0700;

    
        //echo ;
    if(!isset($_SESSION['id'])){
        exec($makeDirectory, $permission);//$_SESSION['id']
        $query = "INSERT INTO `sessionInfo` (`sessionId`) VALUES ('$sessionId')";
        echo $query;
        $result=mysqli_query($link, $query);

        $_SESSION['id'] = $sessionId;
    }

            //echo ("{$_SESSION['id']}");
    

    echo $result;

//  echo $user->getLink();
    echo '<h3>hi</h3>';
    $tempName = $_FILES['fileUpload']['tmp_name'];
    $theFile = $_FILES['fileUpload']['name'];
    
    echo "The name of the file is ".$theFile."'<br>'";
    $fileWithoutExtension=substr($theFile,0,-4);
    $type = $_FILES['fileUpload']['type']; 
//  echo "type is ". $type."<br>";
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
//  echo "type is ". $type."<br>";
    // if($trackFileType=="mp4")
    //  echo "hello";
    
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
    $num_rows    = mysqli_num_rows($imageResult);
    //$go = 0;
    $currentId = $imageRow['id'];
    
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
        $defaultConversion = "$changeDirectory ffmpeg -loop 1 -i ../image.jpg -i \"" . $theFile."\" -c:v libx264 -c:a aac -strict experimental -b:a 192k -shortest -vf scale=800:400 -pix_fmt yuv420p \"" . $fileWithoutExtension."\".mp4";
        echo "no " .$defaultConversion;
        //exec("cd fileconverter && " .$defaultConversion);
        exec($defaultConversion, $output,$return);
            echo "<br>Return is ". $return;
            echo 'Download';
        if($return==0)
            $download=0;

        ?>

         <script>
             download = <?php echo json_encode($download); ?>;

             downloadLink=<?php echo json_encode($sessionId."/".$fileWithoutExtension); ?>;

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
        echo "yes ". $imageConversion;
        //exec("cd fileconverter && " .$defaultConversion);
        exec($imageConversion, $output,$return);
        echo " Return is ". $return;
        if($return==0)          
            ?>
        <!-- <a href=" http://45.79.163.144/fileconverter/<?php echo $sessionId."/".$fileWithoutExtension ?>.mp4" target="_blank" download>Download here</a>"; -->
        <script>
            $('#myModal').modal('show');
        </script> 

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Download your mp4!</h4>
                  </div>
                  <div class="modal-body">
                    <a href=" http://45.79.163.144/fileconverter/<?php echo $sessionId."/".$fileWithoutExtension ?>.mp4" target="_blank" download>Download here</a>";
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>
            
        <?php
        $query = "DELETE FROM `withImage` WHERE `id` = ". $currentId ;
        echo "<br>".$query;
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
             <li class="active"><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
   </div>
 

    <div id="mainContent">
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
                    <div id="download">                     
                        <video id="myVideo" controls autoplay>
                          <source id="mp4_src" src="" type="video/mp4">
                          <source id="ogg_src" src="" type="video/ogg">
                          Your browser does not support HTML5 video.
                        </video>
                    </div>
                </div>
       
             <div id="fileUpload" >         
                <div class="row " >            
                    <!-- <form method="post" action="userInterface.php">
                       <label class="control-label">Select Audio File</label>
                      <input id="input-7" name="fileUpload" multiple type="file" class="file file-loading text-center" data-allowed-file-extensions='["mp3", "wav", "m4a"]'> 
                    </form> -->

                    <form action="userInterface.php" method="post" enctype="multipart/form-data">
                        Select Track to upload:<br>
                        <input type="file" name="fileUpload" class="file" id="fileToUpload"><br>
                  <!--       <input type="submit" value="Upload Track" name="submit"> -->
                    </form>

                    <br><br>

                    <label>Would you like to add your own image to the mp4?</label>
                
                    <form action="userInterface.php" method="post">
                      <input id="no" type="radio" name="image" value="no" checked onchange="showImageUpload(this)"> No
                  
                      <input id="yes" type="radio" name="image" value="yes" onchange="showImageUpload(this)"> Yes
                    </form>

                    <br><br>

                   <div id="imageUpload" style="visibility:hidden">
                       <form method="post" action="userInterface.php" enctype="multipart/form-data" >
                           <label class="control-label">Select Image</label>
                           <input  type="file" name="image" class="file" data-allowed-file-extensions='["png", "gif", "jpg", "jpeg"]' >
                        </form>
                    </div>

                     <br>
                     
                </div>

                <br><br>
                <div class = "row">
                    <div class="col-sm-offset-5 col-sm-2 text-center">
                        <form method ="post" action="userInterface.php">
                            <input type="submit" name = "convert" class="btn btn-primary btn-lg id" id ="convert" value="Convert!"/> 
                        </form>
                    </div>
                 </div>
            </div>
        </div>

    </div>

    <button onclick="hideMainContent();">click</button>
    <script>



 var downloadContent = '<div align="center" class="embed-responsive embed-responsive-16by9">\
                            <video autoplay loop class="embed-responsive-item">\
                                 <source src='+downloadLink+'.mp4' +'type=video/mp4>\
                            </video> \
                            </div> ';

    
 $(window).load(function() {
        // $('#loading').hide();
        document.getElementById('myVideo').style.display='none';
       
        if(download==0){
            // hideMainContent();
             var vid = document.getElementById("myVideo");
             var extension = ".mp4";
            hideFileUploadContent();
            var link=  downloadLink.concat(extension);
            vid.src=link;
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
        }



     });




                            
                               
                        
                    

     // var downloadContent=' <div class="modal hide fade" id="myModal">\
     //      <div class="modal-header">\
     //        <a class="close" data-dismiss="modal">×</a>\
     //        <h3>Modal header</h3>\
     //      </div>\
     //      <div class="modal-body">\
     //         <a href=" http://45.79.163.144/fileconverter/"' + downloadLink + ' ".mp4" target="_blank" download>Download here</a>\
     //      </div\
     //      <div class="modal-footer">\
     //        <a href="#" class="btn">Close</a>\
     //        <a href="#" class="btn btn-primary">Save changes</a>\
     //      </div>\
     //     </div>';

    // if(download==0){
    //     document.getElementById('fileUpload').innerHtml ="";

    // }


    var myEl = document.getElementById('convert');

    // myEl.addEventListener('click', function() {
    //     jQuery('#fileUpload div').html('');
    //        // $('#loading').show();
    //     //alert('Hello world');
    // }, false);

    function showImageUpload(e){
        document.getElementById('imageUpload').style.visibility=e.checked && e.id =='yes' ? 'visible' : 'hidden';           
    }

    function hideFileUploadContent(){
        document.getElementById('fileUpload').style.visibility='hidden';
    }
    // function hideMainContent(){
    //     document.getElementById('mainContent').style.visibility='hidden';
    // } 

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