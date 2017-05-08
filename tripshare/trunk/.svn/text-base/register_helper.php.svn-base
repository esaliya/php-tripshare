<?php
/**
 * Created by PhpStorm.
 * User: goyals
 * Date: Nov 27, 2010
 * Time: 6:36:40 PM
 * To change this template use File | Settings | File Templates.
 */
session_start();
require('db_access.php');
 
if (!isset($_POST['parent'])) {

    header("location:index.php");
    exit;
 }


$fname= $_POST['fname'];
$lname= $_POST['lname'];
$email= $_POST['email'];
$username= $_POST['username'];
$password= $_POST['password'];
$confirm= $_POST['cpassword'];
$homepage = $_POST['homepage'];
$security_q= $_POST['securityQ'];
$security_a= $_POST['securityA'];

$content = file_get_contents("register.php");
$isInvalid=0;

if(!$fname) {

    $isInvalid=1;
    $content=preg_replace("/<!--fname error-->/","<br>&nbsp;First Name cannot be empty",$content) ;
}

if(!$username) {

    $isInvalid=1;
    $content=preg_replace("/<!--username error-->/","<br>&nbsp;User Name cannot be empty",$content) ;
}

if(!$email) {

    $isInvalid=1;
    $content=preg_replace("/<!--email error-->/","<br>&nbsp;Email cannot be empty",$content) ;
}
elseif(!isValidEmail($email))
{
    $isInvalid=1;
    $content=preg_replace("/<!--email error-->/","<br>&nbsp;Email format is not correct",$content) ;
}

if(!$password) {

    $isInvalid=1;
    $content=preg_replace("/<!--password error-->/","<br>&nbsp;Password cannot be empty",$content) ;
}

if(!$confirm) {

    $isInvalid=1;
    $content=preg_replace("/<!--confirm error-->/","<br>&nbsp;Confirm Password cannot be empty",$content) ;
}

elseif($password){

    if($password!= $confirm) {

        $isInvalid=1;
        $content=preg_replace("/<!--confirm error-->/","<br>&nbsp;Confirm Password doest not match",$content) ;
    }
}

if(!$security_q) {

    $isInvalid=1;
    $content=preg_replace("/<!--question error-->/","<br>&nbsp;Security Question cannot be empty",$content) ;
}

if(!$security_a) {

    $isInvalid=1;
    $content=preg_replace("/<!--answer error-->/","<br>&nbsp;Security Question cannot be empty",$content) ;
}

if($isInvalid) {
    echo $content;
    exit;
}

dbConnect();

$query= "SELECT * FROM members WHERE uid = '$username'";

$result = mysql_query($query);

$count = mysql_num_rows($result);

if($count > 0)
{
    $content=preg_replace("/<!--username error-->/","<br>&nbsp;User Name Already Taken",$content) ;
    echo $content;
    exit;
}
else
{
    $password=sha1($password);
    $query = "INSERT INTO members ( uid,fname,lname, email,homepage, password, sec_q, sec_a) VALUES ('$username', '$fname','$lname', '$email','$homepage', '$password'
        ,'$security_q','$security_a')";

    $result = mysql_query($query);

    if($result)
    {
        $_SESSION['authUser'] = $username;
        $_SESSION['authRole'] = "user";
        $_SESSION['authFname'] = $fname;
        $_SESSION['authLname'] = $lname;

        header("location:success.html");
        exit;
    }
}

function isValidEmail($email){
    $pattern = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

    if (preg_match($pattern, $email)) {
        return true;
    }
    else {
        return false;
    }
}
?>


