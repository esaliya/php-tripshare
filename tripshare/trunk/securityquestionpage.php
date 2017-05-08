<?php
session_start();
require("links.php");
require("db_access.php");
require('trip_share_exception.php');
dbConnect();

if (!isset($_SESSION['pendingUsr'])) {
    // Bad request
    header("Location:index.php");
}

$pendingUsr = $_SESSION['pendingUsr'];
$query = "SELECT * FROM members where uid='$pendingUsr';";
$result = query($query);
if (!$result || mysql_num_rows($result) != 1) {
    // Something went wrong
    $_SESSION['errorid'] = TripShareException::OOPS;
    header("Location: error.php");
}
$r=mysql_fetch_array($result);

if (isset($_POST['ans'])) {
    $myans = $_POST['secans'];
    if ($r['sec_a'] != $_POST['secans'])
    {
        $_SESSION['errorid'] = TripShareException::SECURITYANS;
        header("location:error.php");
    }
    else
    {
        $_SESSION['authUser'] = $r['uid'];
        $_SESSION['authRole'] = $r['role'];
        $_SESSION['authFname'] = $r['fname'];
        $_SESSION['authLname'] = $r['lname'];
        $email = $r['email'];
        $subject = "Password reset for Tripshare";
        $message = "Dear User,we understood that your security answer is correct.Here ";
        //mail("$email","$subject",)
        header("location:index.php");

    }
}

?>
<!--test code for navigation by Saliya-->
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

        </div>
        <div class="logo">
            <h1><a href="#" title="TripShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>
    </div>

    

    <div class="center">
        <br><br>
        
        <h3> Trouble Accessing Your Account :</h3>

        <div class="box">

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <table>
                    <tr>
                        <td valign="middle">
                            <label>
                                <h4>Security Question</h4>
                            </label>
                        </td>
                        
                        <td>
                            <label>
                                 <h4> <?php echo $r['sec_q']; ?> </h4>
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <td valign="middle">
                           <label>
                               <h4>Enter the answer:</h4>
                           </label>
                        </td>
                        <td>
                            <input type="password" name="secans" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                <label><input type="submit" name="ans" value="submit" class="submit" /></label>
                        </td>
                    </tr>
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


