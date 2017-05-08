<?php
/**
 * User: sekanaya
 *       sekanaya at cs dot indiana dot edu
 */
session_start();
echo ("PHP Session ID: " . session_id());

 ?>

<html>
<body>
<?php
if (!isset($_SESSION['authUser'])) { ?>
<form action="authenticate.php">
    <label>Username: <input type="text" name="uname"/></label>
    <label>Password: <input type="password" name="pwd"/></label>
    <label><input type="submit" name="submit" value="Submit"/></label>
</form>
<?php }

else {
    echo ("<br>Welcome " . $_SESSION['authUser']); ?>
    <a href="logout.php">Logout</a>
<?php } ?>
</body>
</html>
 
