<?php

  ini_set('display_errors',1);
  error_reporting(E_ALL);

  $name = "";
  $email = "";
  $message = "";

  $errName    = "";
  $errEmail   = "";
  $errMessage = "";
  $errHuman   = "";
  $result     = "";

    if (isset($_POST["submit"])) {
        $name = isset($_POST['name']);
        $email = isset($_POST['email']);
        $message = isset($_POST['message']);
        $human = intval($_POST['human']);
        $from = 'Demo Contact Form'; 
        $to = 'avinash.jairam@gmail.com'; 
        $subject = 'Message from Contact Demo ';

        
        
        $body = "From: $name\n E-Mail: $email\n Message:\n $message";
 
        // Check if name has been entered
        if (!$_POST['name']) {
            $errName = 'Please enter your name';
        }
        
        // Check if email has been entered and is valid
        if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errEmail = 'Please enter a valid email address';
        }
        
        //Check if message has been entered
        if (!$_POST['message']) {
            $errMessage = 'Please enter your message';
        }
        //Check if simple anti-bot test is correct
        if ($human !== 5) {
            $errHuman = 'Your anti-spam is incorrect';
        }
 
// If there are no errors, send the email
if (!$errName && !$errEmail && !$errMessage && !$errHuman) {
    if (mail ($to, $subject, $body, $from)) {
        $result='<div class="alert alert-success">Thank You! I will be in touch</div>';
    } else {
        $result='<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later</div>';
    }
}
    }
?>

<!DOCTYPE html>
<!-- release v4.2.8, copyright 2014 - 2015 Kartik Visweswaran -->
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <title>About</title>
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
             <li><a href="http://45.79.163.144/fileconverter/userInterface.php">Home</a></li>
            <li ><a href="./about.php">About</a></li>
            <li class="active"><a href="./contact.php">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
   </div>
 

    <div id="mainContent">  
        <div class="container contentContainer">
            <div class = "row">  
              <h3>Contact Us</h3>
             <div class="col-md-8 ">                             
                  <form class="form-horizontal" role="form" method="post" action="contact.php">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" placeholder="First & Last Name" value="<?php echo htmlspecialchars(isset($_POST['name'])); ?>">
                                <?php echo "<p class='text-danger'>$errName</p>";?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php echo htmlspecialchars(isset($_POST['email'])); ?>">
                                <?php echo "<p class='text-danger'>$errEmail</p>";?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-2 control-label">Message</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="4" name="message"><?php echo htmlspecialchars(isset($_POST['message']));?></textarea>
                                <?php echo "<p class='text-danger'>$errMessage</p>";?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="human" class="col-sm-2 control-label">2 + 3 = ?</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="human" name="human" placeholder="Your Answer">
                                <?php echo "<p class='text-danger'>$errHuman</p>";?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <?php echo $result; ?>    
                            </div>
                        </div>
                    </form>          
              </div>     
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


    <!-- <button onclick="hideMainContent();">click</button> -->
    

    </body>
</html>