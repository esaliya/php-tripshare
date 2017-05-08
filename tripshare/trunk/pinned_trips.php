<?php
//pinned_trips.php
//modified by Eric
//please feel free to make any change
session_start();
require('links.php');
require('db_access.php');
$_TABLE_NAME = "pinned_trips";

if(isset($_SESSION["authUser"])) {
  $uid = $_SESSION["authUser"];
  $role = $_SESSION["authRole"];
  //$uid = "uid_102";//[test only]

  dbConnect();
  $message = "";

  //remove a specific pinned trip if user click remove
  if (isset($_GET["q"])) {
	$tid = $_GET["q"];//used only for delete
	  if (isset($tid)) {
		$query = "DELETE FROM $_TABLE_NAME WHERE uid='$uid' AND tid='$tid';";
		$result = query($query);
		//mysql_query($query);
			if($result) {
				$message = "<p>TripID: $tid is deleted!</p>";
			}
			else {
				$message = "<p>There is a problem with the query: " . mysql_error() . "</p>";
			}
	  }
  }
  //show each pinned trips for particular user
  //noticed that the uid should get from session.
  $query = "select * from $_TABLE_NAME where uid='$uid';";
  $result = query($query);
  $num = mysql_numrows ($result);

  switch ($num) {
	case 0:
		$message .= "<p>There is no pinned trip.</p><br>";
		break;
	case 1:
		$message .= "<p>There is 1 pinned trip.</p><br>";
		break;
	case ($num > 1):
		$message .= "<p>There are $num pinned trips.</p><br>";
		break;
	default:
		$message .= "<p>Error!</p><br>";
  }
  // show pinned trips and create a delete option for each pinned trip
  for ($i = 0; $i < $num; $i++) {
	$tid = mysql_result ($result, $i, 'tid');
	$message .= "<p>TripID: $tid &nbsp";
	$message .= "<a href='pinned_trips.php?q=$tid'><input type=\"submit\" class=\"submit\" value=\"Remove\"></a></p>";
  }
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
    <?php echo genNavi("Pinned Trips"); ?>
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
        <h3>All Pinned Trips:</h3>
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
        <p><a href="contact.php">Contact</a> <br/>
            &copy; Copyright 2010 TripShare</p>
    </div>

</div>
</body>
</html>