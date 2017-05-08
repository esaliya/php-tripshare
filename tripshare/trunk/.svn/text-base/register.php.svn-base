<?php
include('links.php');
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

     <div class="gap"></div>

    <div class="right">
        <h3>Register Now</h3>
        <form method="post" action="register_helper.php">
         <label for="hiddenParent"><input id="hiddenParent" name="parent" value="register" type="hidden"/> </label>
             <table>
             <tr><td>First Name:</td><td><input type="text" name="fname"  maxlength="30"><!--fname error--></td></tr>
             <tr><td>Last Name:</td><td><input type="text" name="lname"  maxlength="30"><!--lname error--></td></tr>
             <tr><td>Username:</td><td><input  type="text" name="username"  maxlength="30"><!--username error--></td></tr>
             <tr><td>Email:</td><td><input  type="text" name="email" maxlength="30"><!--email error--></td></tr>
             <tr><td>Password:</td><td><input  type="password" name="password" maxlength="30"><!--password error--></td></tr>
             <tr><td>Confirm Password:</td><td><input type="password" name="cpassword" maxlength="30"><!--confirm error--></td></tr>
             <tr><td>Home Page:</td><td><input type="text" name="homepage" maxlength="30"><!--homepage error--></td></tr>
             <tr><td>Security Question:</td><td><select name="securityQ"><option>What is your favourite color?</option
             <option>What is your mother's maiden name?</option><option>What is your hometown?</option><option>What is your pet-name?</option>
             </select><!--question error--></td></tr>
             <tr><td>Security Answer:</td><td><input type="text" name="securityA"  maxlength="30"><!--answer error--></td></tr>
             <tr><td colspan="2" align="right"><input type="submit" name="subjoin" value="Register" class="submit"></td></tr>
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



