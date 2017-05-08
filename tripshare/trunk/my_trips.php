<?php
//my_trips.php
//modified by Eric
session_start();
require('links.php');
require('db_access.php');
function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
}

$_TABLE_NAME = "trips";

if(isset($_SESSION["authUser"])) {
  $uid = $_SESSION["authUser"];
  $role = $_SESSION["authRole"];
  //$uid = "uid_102"; //[use for test only]

  dbConnect();
  $message = "";

  if (isset($_GET["q"])) {
	$tid = $_GET["q"];//used only for delete
	  //if (isset($tid)) {

    $query = "DELETE FROM restaurants WHERE tid='$tid';";
	$result = query($query);
    $query = "DELETE FROM hotels WHERE tid='$tid';";
	$result = query($query);
    $query = "DELETE FROM attractions WHERE tid='$tid';";
	$result = query($query);
    $query = "DELETE FROM transports WHERE tid='$tid';";
	$result = query($query);
    $query = "DELETE FROM avoids WHERE tid='$tid';";
	$result = query($query);

    //get all the trip_dest where tid = $tid
    $query = "SELECT * FROM trip_destinations WHERE tid='$tid';";
    $result = query($query);
    $num = mysql_numrows ($result);
    for ($i = 0; $i < $num; $i++) {
        $r = mysql_fetch_array($result);
        //check if there exist any trip that has the same country, state, province and city
        $query = "SELECT * FROM trip_destinations WHERE country = $r[country] AND state = $r[state] AND province = $r[province] AND city = $r[city];";
        $result = query($query);
        $count = mysql_numrows ($result);
        //if there is only one(itself), we can delete the row in destination table
        if($count == 1) {
            $query = "DELETE FROM destinations WHERE country = $r[country] AND state = $r[state] AND province = $r[province] AND city = $r[city];";
            $result = query($query);
        }
    }
    $query = "DELETE FROM trip_destinations WHERE tid = '$tid';";
    $result = query($query);
    $query = "DELETE FROM ratings WHERE tid = '$tid';";
    $result = query($query);
    $query = "DELETE FROM pinned_trips WHERE tid = '$tid';";
    $result = query($query);

    //[Important: delete every .svn folder before you use the following code]
    //before delete any row in photo table, we need to delete the actual pictures
    $query = "SELECT DISTINCT * FROM photos WHERE tid = '$tid';";
    $result = query($query);
    $num = mysql_numrows ($result);
    for ($i = 0; $i < $num; $i++) {
        $r = mysql_fetch_array($result);
        rrmdir($r['disk_location']);
    }
    //delete the row in photo table
    $query = "DELETE FROM photos WHERE tid = '$tid';";
    $result = query($query);

    //finally we can delete the trip
    $query = "DELETE FROM trips WHERE tid='$tid';";
    $result = query($query);
    if($result) {
      $message = "<p>TripID: $tid is deleted!</p>";
    }
    else {
        $message = "<p>There is a problem with the query: " . mysql_error() . "</p>";
    }
  }

  $query = "select * from $_TABLE_NAME where uid='$uid';";
  $result = query($query);
  $num = mysql_numrows ($result);
  //print message
  switch ($num) {
	case 0:
		$message .= "<p>You have no post</p><br>";
		break;
	case ($num >= 1):
		$message .= "<p>You have $num posts:</p><br>";
		break;
	default:
		$message .= "<p>Error!</p><br>";
  }

  $message .= "<table>";
  // show all trips that the user posted with link to view the trip
  for ($i = 0; $i < $num; $i++) {
	$tid = mysql_result ($result, $i, 'tid');
	$title = mysql_result ($result, $i, 'title');
	$posted_date = mysql_result ($result, $i, 'posted_date');
	$message .= "<tr><td><a href='trip_view.php?tid=$tid'> $title </a></td>";
	$message .= "<td> $posted_date </td>";
    $message .= "<td><a href='trip_design.php?tid=$tid'><input type='submit' class='submit' value='Edit'/></a></td>";
	$message .= "<td><a href='my_trips.php?q=$tid'><input type='submit' class='submit' value='Remove'/></a></td></tr>";

  }
  $message .= "</table>";
}
else {
   header("location:index.php");
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
        </div>
        <div class="logo">
            <h1><a href="#" title="TrpShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>
    </div>


    <div class="bar" id="navi">
    <!--Navigation Bar-->
    <?php echo genNavi("My Trips"); ?>
    </div>

    <div class="search_field">
        <form method="post" action="search_helper.php">
            <div class="search_form">
                <p>Search Trips: <label> <input type="text" name="simpleSearchCity" class="search"/> </label>
                    <input type="submit" name="submit" value="Search" class="submit"/> <a class="grey" href="search.php">Advanced</a></p>
            </div>
        </form>

        <p>&nbsp;</p>
    </div>

    <div class="left">
        <h3>How TripShare Works:</h3>
        <div class="left_box">
          <?php echo "$message"; ?>
        </div>
    </div>

	<div class="right">

        <h3>Most Popular Trips:</h3>
        <?php echo(popularTrips()); ?>

        <h3>Recently Shared Trips:</h3>
        <?php echo(recentTrips()); CloseDB();?>
    </div>
    <div class="footer">
        <p><a href="#">Contact</a> <br/>
            &copy; Copyright 2010 TripShare</p>
    </div>

</div>
</body>
</html>