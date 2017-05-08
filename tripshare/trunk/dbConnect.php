<?php
//$dbHost = "silo.cs.indiana.edu";
//$dbUserAndName = "b561_f10_41";
//$dbPass = "group**41";
$dbHost = "localhost";
$dbUserAndName = "root";
$dbPass = "******7";

mysql_connect ($dbHost, $dbUserAndName, $dbPass)
     or die ("Cannot connect to host $dbHost with user $dbUserAndName and the password provided.");
	 
//$dbUserAndName
mysql_select_db ("tripshare_db") or die ("Database $dbUserAndName not found on host $dbHost");

?>
