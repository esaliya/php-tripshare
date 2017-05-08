<?php //modified by Eric
//please feel free to make any change
//include "dbConnect.php";
session_start();
require("links.php");
require('db_access.php');


if(isset($_SESSION["authUser"])) {
    dbConnect();
    $uid = $_SESSION["authUser"];
    $role = $_SESSION["authRole"];

    if(isset($_GET["uid"])) {
        $uid = $_GET["uid"];
        $owner = false;
    }
    else {$owner = true;}

    //if($role == "admin") {
    //do something different
    //}
    //query
    $_TABLE_NAME = "members";
    $query = "select * from $_TABLE_NAME where uid='$uid'";
    $result = mysql_query($query);

    $uid = mysql_result($result, 0, 'uid');
    $fname = mysql_result($result, 0, 'fname');
    $lname = mysql_result($result, 0, 'lname');
    $email = mysql_result($result, 0, 'email');
    $homepage = mysql_result($result, 0, 'homepage');
    $Profile = $fname;
    $userInfo = "<p>User ID: $uid</p><br>
  <p>First Name: $fname</p><br>
  <p>Last  Name: $lname</p><br>
  <p>Email: $email</p><br>
  <p>Homepage: $homepage</p><br>";

    if($owner == true) {
        $userInfo .= "<p><a href='change_pass.php'>Change Password</a></p>";
    }
}
else {
    header("location:index.php");
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
            <h1><a href="#" title="TrpShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>
    </div>


    <div class="bar" id="navi">
        <!--Navigation Bar-->
    <?php echo genNavi("Profile"); ?>
    </div>

    <div class="search_field">
        <form method="post" action="search_helper.php">
            <div class="search_form">
                <p>Search Trips: <label> <input type="text" name="simpleSearchCity" class="search"/> </label>
                    <input type="submit" name="submit" value="Search" class="submit"/> <a class="grey" href="search.php">Advanced</a></p>
            </div>
        </form>

        <p>&nbsp;</p>
    </div>

    <div class="left">
        <h3><?php echo "$Profile's Profile"; ?></h3>
        <div class="left_box">
        <?php echo "$userInfo"; ?>
        </div>
    </div>

    <div class="right">

       <h3>Most Popular Trips:</h3>
        <?php echo(popularTrips()); ?>

        <h3>Recently Shared Trips:</h3>
        <?php echo(recentTrips()); ?>
    </div>
    <div class="footer">
        <p><a href="contact.php">Contact</a> <br/>
            &copy; Copyright 2010 TripShare</p>
    </div>

</div>
</body>
</html>