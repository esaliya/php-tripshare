<?php
//
    session_start();
    require('links.php');
    require('db_access.php');
    dbConnect();

 $to = "goyalsumit503@gmail.com";
 $subject = $_POST['subject'];
 $body = $_POST['question'];
$from=$_POST['email'];
$headers = "From: $from";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
    <link rel="stylesheet" href="styles/style.css" type="text/css"/>
    <title>TripShare</title>
</head>
<body>
<div class="content">
    <div class="header">
        <div class="top_info_right">
        <?php echo genTopLinks(); ?>
            <br/>
        </div>
        <div class="logo">
            <h1><a href="index.php" title="TrpShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>
    </div>
    <div class="bar" id="navi">
        <!--Navigation Bar-->
    <?php echo genNavi("Contact"); ?>
    </div>
    <div class="left">
      <div class="left_box">
          <?php
           if(mail($to, $subject, $body,$headers)){
              echo"<h1 align=\"center\" font-size=12x> Email Successfully sent to Admin</h1>";
            }
             ?>
      </div>
     </div>
    </div>
</body>
</html>
