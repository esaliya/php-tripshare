<?php

$dbcnx = @mysql_connect("localhost", "root", "");
if (!$dbcnx) {
    echo("<p>Couldn't connect to DB at this time</p>");
    exit();
}

if (!@mysql_select_db("tripshare_db") ) {
  echo( "<P>Unable to locate the tripshare_db database at this time.</P>" );
  exit();
}

echo("<p>great! connected to DB successfully</p>");

$result = mysql_query("SELECT fname,lname FROM members");
if (!$result) {
  echo("<P>Error performing query: " . mysql_error() . "</P>");
  exit();
}

while ( $row = mysql_fetch_array($result) ) {
  echo("<P>" . $row["fname"] . " " . $row["lname"] . "</P>");
}

mysql_close($dbcnx)
?>
