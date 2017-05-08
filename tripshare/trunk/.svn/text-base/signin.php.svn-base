<?php
session_start();
require('links.php');
require('db_access.php');
require('trip_share_exception.php');

$result=NULL;
if(isset($_SESSION["authUser"])) {
    header('location:index.php');
}
else
{
if(isset($_POST['uname']) && isset($_POST['pwd']))
{
    dbConnect();
    $uname = $_POST['uname'];
    $pwd = $_POST['pwd'];
    $pwd = sha1($pwd);
    $query = "SELECT sm.user_uid from suspended_members sm where sm.user_uid = '$uname';";
    $result = query($query);

    if ($result && mysql_num_rows($result)>0) {
        // Suspended user. So go to error
        $_SESSION['errorid'] = TripShareException::SUSPENDED;
        header("location:error.php");
        exit();
    }

    $query= "SELECT m.uid,m.fname,m.lname,m.password,m.role FROM members m
    WHERE  m.uid = '$uname' AND m.password='$pwd'";
    $result = query($query);

    if ($result) {
        // If result matched $myusername and $mypassword, table row must be 1 row
        $count = mysql_num_rows($result);

        if ($count == 1) {
            $row = mysql_fetch_array($result);
            $_SESSION['authUser'] = $_POST['uname'];
            $_SESSION['authRole'] = $row['role'];
            $_SESSION['authFname'] = $row['fname'];
            $_SESSION['authLname'] = $row['lname'];
            header("location:index.php");
        }
        else
        {
            $_SESSION['errorid'] = TripShareException::OOPS;
            header("location:error.php");
        }
    }

    else
    {
        $_SESSION['errorid'] = TripShareException::OOPS;
        header("location:error.php");
    }
}
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
    <?php echo genNavi("Sign In"); ?>
    </div>

    <div class="gap"></div>
    <div class="right">
        <h3> Login :</h3>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table>
                <tr>
                    <td valign="middle">Username:</td>
                    <td><label><input type="text" name="uname"/></label></td>
                </tr>
                <tr>
                    <td valign="middle">Password:</td>
                    <td><label><input type="password" name="pwd" "/></label></td>
                                    </tr>
                <tr>
                    <td></td>
                    <td align="right"><label><input type="submit" name="submit" value="Sign in" class="submit"/></label></td>
                </tr>
                <tr>
                    <td colspan="2" align="left"><a href="recover.php">Forgot your password?</a></td>
                </tr>
            </table>
        </form>

    </div>
    <div class="footer">
        <p><a href="contact.php">Contact</a> <br/>
            &copy; Copyright 2010 TripShare</p>
    </div>

</div>
</body>
</html>