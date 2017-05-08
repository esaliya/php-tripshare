

<?php
require("db_access.php");
$myusername=$_POST['uname'];
$mypassword=$_POST['pwd'];

dbConnect();
$query="SELECT * FROM `tripshare_db`.`users` where username='$myusername' and password='$mypassword'";
//$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$mypassword'";
$result=query($query);
$count=mysql_num_rows($result);
// If result matched $myusername and $mypassword, table row must be 1 row

if($count==1)
{
    // Register $myusername, $mypassword and redirect to file "login_success.php"
    session_register("myusername");
    session_register("mypassword");
    echo "success";
    header("location:index.php");
}
else
{
    header("location:signin.php");
    echo "Login Failed,Please try again ";
}
?>