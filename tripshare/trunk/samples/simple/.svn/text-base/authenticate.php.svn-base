<?php
/**
 * User: sekanaya
 *       sekanaya at cs dot indiana dot edu
 */
session_start();
if (validate($_REQUEST['uname'], $_REQUEST['pwd'])){
    $authRole = 'user'; // todo: access DB and get role
    $authUser = $_REQUEST['uname'];
    $_SESSION['authUser'] = $authUser;
    $_SESSION['authRole'] = $authRole;
    header('Location: login.php');
    
}

function validate($uname, $pwd) {
//    todo: validate logic
    return true;
}

 ?>
 
