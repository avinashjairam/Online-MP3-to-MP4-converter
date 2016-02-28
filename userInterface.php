<?php include './fileConverterPHPcode.php' ?>


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
     <script src="./js/fileConverterJavaScript.js" type="text/javascript"></script>

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



    </body>
</html>