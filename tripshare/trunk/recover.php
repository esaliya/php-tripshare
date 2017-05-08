<?php
session_start();
require("links.php");
require("db_access.php");
require('trip_share_exception.php');

$result = "";
if (isset($_POST['next'])) {
    $myusername = $_POST['uid'];
    dbConnect();

    $query = "SELECT sm.user_uid from suspended_members sm where sm.user_uid = '$myusername';";
    $result = query($query);
    // If result matched $myusername and $mypassword, table row must be 1 row

    if ($result && mysql_num_rows($result) > 0) {
        // Suspended user. So go to error
        $_SESSION['errorid'] = TripShareException::SUSPENDED;
        header("location:error.php");
        exit();
    }

    $query = "SELECT * FROM members where uid='$myusername' ";
    $result = query($query);
    if ($result && mysql_num_rows($result) == 1) {
        $_SESSION['pendingUsr'] = $_POST['uid'];
        header("location:securityquestionpage.php");
    } else {
        $_SESSION['errorid'] = TripShareException::WRONGUSERNAME;
        header("location:error.php");
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
            <h1><a href="index.php" title="TrpShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>
    </div>


    <div class="bar" id="navi">
        <!--Navigation Bar-->
        <?php echo genNavi("Home"); ?>
    </div>

    

    <div class="center">
        <br><br>

        <h3> Trouble Accessing Your Account :</h3>

        <div class="box">

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <table>
                    <tr>
                        <td>
                            <label><h4> Username</h4></label>
                        </td>
                        <td>
                            <label>
                                <input type="text" name="uid">
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                    <td>
                        
                    </td>
                    <td align="right">
                        <label>
                            <input type="submit" name="next" value="next" class="submit">
                        </label>
                    </td>

                </tr>
                <br>    
                </table>

            </form>


        </div>
    </div>


</div>
<div class="footer">
    <p><a href="contact.php">Contact</a> <br/>
        &copy; Copyright 2010 TripShare</p>
</div>
</body>
</html>