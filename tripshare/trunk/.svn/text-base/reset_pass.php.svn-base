<?php //modified by Eric
//please feel free to make any change
//include "dbConnect.php";
session_start();
require("links.php");
require('db_access.php');
require('trip_share_exception.php');

if(isset($_SESSION["authUser"]))
{
    dbConnect();
    $uid = $_SESSION["authUser"];
    $role = $_SESSION["authRole"];

    if(isset($_GET['q']))
    {
        $_TABLE_NAME = "members";
        $query = "select * from $_TABLE_NAME where uid='$uid'";
        $result = query($query);
        $r=mysql_fetch_array($result);
        $oldpass = sha1($_POST['oldPass']);
        $newpass = sha1($_POST['newPass']);
        $renewpass = sha1($_POST['reNewPass']);
    if ($r['password'] == $oldpass && $newpass == $renewpass)
    {

        $query1 = "update $_TABLE_NAME set password ='$newpass' where uid = '$uid'; ";
        query($query1);
        $_SESSION['errorid'] = TripShareException::PASSWORDRESET;
        header("location:error.php");
    }
    else
    {
        $_SESSION['errorid'] = TripShareException::WRONGOLDPASSWORD;
        header("location:error.php");
    }
    }

}
else
{
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
            <br/>
        </div>

        <div class="logo">
            <h1><a href="index.php" title="TripShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>

    </div>
    <div class="bar" id="navi">
        <!--Navigation Bar-->
    <?php echo genNavi("Profile"); ?>
    </div>

    
    <div class="search_field">
        <form method="post" action="?">
            <div class="search_form">
                <p>Search Trips: <input type="text" name="search" class="search"/>
                    <input type="submit" value="Search" class="submit"/>
                    <a class="grey" href="#">Advanced</a></p>
            </div>
        </form>

        <p>&nbsp;</p>
    </div>
<div class="left">
   <form method="post" action="reset_pass.php?q='reset'">
            <table>
                <tr>
                    <td valign="middle">Old Password:</td>
                    <td><label><input type="password" name="oldPass"/></label></td>
                </tr>
                <tr>
                    <td valign="middle">New Password:</td>
                    <td><label><input type="password" name="newPass"/></label></td>
                </tr>
                <tr>
                    <td valign="middle">Re-enter the new Password:</td>
                    <td><label><input type="password" name="reNewPass"/></label></td>
                </tr>

                <tr>

                    <td align="right"><label><input type="submit" name="submit" value="Confirm" class="submit"/></label></td>
                </tr>
                
            </table>
        </form>

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