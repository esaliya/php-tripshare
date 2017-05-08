<?php
session_start();
include('links.php');
require('db_access.php');
dbConnect();

$TABLE_NAME = "members";

if (isset($_SESSION['authUser'])) {
    $authUser = $_SESSION['authUser'];
    if ("admin" == $_SESSION['authRole']){

        if(isset($_GET['susid'])) {

        $suspended_uid=$_GET['susid'];
        mysql_query("INSERT INTO suspended_members (admin_uid, user_uid)
        VALUES ('$authUser', '$suspended_uid')");
        }

        if(isset($_GET['ruid'])){

        $resume_uid=$_GET['ruid'];
        mysql_query("DELETE FROM suspended_members WHERE user_uid='$resume_uid'");
        }


        
    } else {
        $_SESSION['errorid'] = TripShareException::NOTADMIN;
        header("location:error.php");
        
    }
}
else {
   echo genNavi("Register");
}
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
        </div>
        <div class="logo">
            <h1><a href="index.php" title="TrpShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>
    </div>
    <div class="bar" id="navi">
    <!--Navigation Bar-->
    <?php echo genNavi("Register"); ?>
        
    </div>

    <div class="search_field">
        <p>&nbsp;</p>
    </div>
    <div class="left">
        <h3>Manage All Users:</h3><br><br>

        <div class="left_box">
        
     <table border=1px align="center" cellspacing="5x" cellpadding="7x">
     <tr><th>ALL USERS</th>
        <th>ACTIONS</th></tr>
     <?php

     $query = "select * from $TABLE_NAME";
     $result = mysql_query($query);
     $num = mysql_numrows ($result);

         
     for ($i = 0; $i < $num; $i++)
     {
         $uid = mysql_result ($result, $i, 'uid');
         $fname = mysql_result ($result, $i, 'fname');
         $lname = mysql_result ($result, $i, 'lname');

         //getting the suspender user id from table
         $query_1= "SELECT user_uid FROM suspended_members";
         $result_1=mysql_query($query_1);
         $count = mysql_numrows($result_1);

            if($count >0){

            while ($row = mysql_fetch_array($result_1)) {

             if(($uid==$row["user_uid"])){
                 
                 $val="ruid";
                 $_SESSION[$i]="Resume";
                 break;
             }
             elseif(($uid==$row["user_uid"]) && ($_SESSION[$i]=="Resume")) {
                  $val="ruid";
                  $_SESSION[$i]="Resume";
                break;
                  }
              else {
                  $val="susid";
                  $_SESSION[$i]="Suspend";
             }

         }
       } else {
                $_SESSION[$i]="Suspend";
                $val="susid";
                
            }

     ?>
    <tr><td><a href="profile_info.php?uid=<?php echo $uid ?>"><?php echo $fname." ". $lname ?></a></td>
        
    <form  action="users.php?<?php print($val .'='. $uid); ?>"  method="post">
    <td><input  type="submit" id="<?php print("btn" . $i); ?>"
     class= "submit" value="<?php echo $_SESSION[$i]; ?>"/>
   </td></form></tr>

<?php }?>

    </table>
    </div>
    </div>
    <div class="right">

        <h3>Most Popular Trips:</h3>
        <?php echo(popularTrips()); ?>

        <h3>Recently Shared Trips:</h3>
        <?php echo(recentTrips()); ?>
    </div>
    <div class="footer">
        <p><a href="contact.php">Contact</a> | <a href="#">Accessibility</a> | <a href="#">Disclaimer</a><br/>
            &copy; Copyright 2010 TripShare</p>
    </div>
</div>
</body>
</html>