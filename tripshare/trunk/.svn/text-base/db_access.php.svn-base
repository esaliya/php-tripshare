<?php
/**
 * Created by PhpStorm.
 * User: Shobana Krishnan
 * Date: Nov 9, 2010
 * Time: 9:24:10 AM
 *

 */


// Database connection variable
$dbcnx = NULL;

// Connect to the database
function dbConnect(){
    global $dbcnx;
    if (is_null($dbcnx)) {
        $dbcnx = @mysql_connect("localhost", "root", "root");
        if (!$dbcnx || !@mysql_select_db("tripshare_db")) {
            return false;
        }
    }
    return true;
}

//Query the database
function query($query){
    global $dbcnx;
    if (!is_null($dbcnx)) {
        $result = mysql_query($query);
        if (!$result) {
            return false;
        }
        return $result;
    }
    return false;
}

//Close the database connection.
function closeDB(){
    global $dbcnx;
    if (!is_null($dbcnx)) {
        return mysql_close($dbcnx);
    }
    return true;
}
?>