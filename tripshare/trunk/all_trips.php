<?php
// todo: Sumit

// Code to handle navigation
session_start();
include('links.php');
require('db_access.php');
dbConnect();

$TABLE_NAME = "trips";

if (isset($_SESSION['authUser'])) {
    if ("admin" == $_SESSION['authRole']) {

        if(isset($_GET['delete_id'])) {

            $delete_tid=$_GET['delete_id'];

            // deleting trip from attractions table
            mysql_query("DELETE FROM attractions WHERE tid='$delete_tid'");

            // deleting trip from avoids table
            mysql_query("DELETE FROM avoids WHERE tid='$delete_tid'");

            // deleting trip from hotels table
            mysql_query("DELETE FROM hotels WHERE tid='$delete_tid'");

            // deleting trip from restaurants table
            mysql_query("DELETE FROM restaurants WHERE tid='$delete_tid'");

            // deleting trip from transports table
            mysql_query("DELETE FROM transports WHERE tid='$delete_tid'");

            // deleting trip from pinned_trips table
            mysql_query("DELETE FROM pinned_trips WHERE tid='$delete_tid'");

            // deleting trip from ratings table
            mysql_query("DELETE FROM ratings WHERE tid='$delete_tid'");


            $query_1 = "select * from trip_destinations WHERE tid='$delete_tid'";
            $result_1 = mysql_query($query_1);
            $num = mysql_numrows($result_1);

            // deleting trips from trip_destinations table
            mysql_query("DELETE FROM trip_destinations WHERE tid='$delete_tid'");


            for ($i = 0; $i < $num; $i++) {

                $country = mysql_result($result_1, $i, 'country');
                $state = mysql_result($result_1, $i, 'state');
                $province = mysql_result($result_1, $i, 'province');
                $city = mysql_result($result_1, $i, 'city');


                $query_2 = "select * from trip_destinations WHERE country='$country' AND state='$state' AND province
                ='$province' AND city='$city'";

                $result_2 = mysql_query($query_2);
                $count = mysql_numrows($result_2);

                if($count == 0){

                    // deleting trip from destinations table
                    mysql_query("DELETE FROM destinations WHERE country='$country' AND state='$state' AND province
                        ='$province' AND city='$city'");
                }
            }

            // deleting trip from photos table
            $query_3 = "select disk_location from photos WHERE tid='$delete_tid'";
            $result_3 = mysql_query($query_3);

            while ($row = mysql_fetch_array($result_3)) {

                unlink($row["disk_location"]);
                $disk_location=$row["disk_location"];
                mysql_query("DELETE FROM photos WHERE disk_location='$disk_location'");
            }

            // deleting trip from trips table
            mysql_query("DELETE FROM trips WHERE tid='$delete_tid'");
        }
        
    }else {
        $_SESSION['errorid'] = TripShareException::NOTADMIN;
        header("location:error.php");
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
    <?php echo genNavi("Trips"); ?>
    </div>
    <div class="search_field">
        <p>&nbsp;</p>
    </div>
    <div class="left">
        <h3> Manage All Trips:</h3><br><br>
      <div class="left_box">
<table border="1" align="center" cellspacing="5x" cellpadding="7x">
     <tr><th>ALL TRIP TITLE</th>
        <th>DELETE TRIP</th></tr>     <?php

        $query = "select * from $TABLE_NAME";
        $result = mysql_query($query);
        $num = mysql_numrows($result);
        
        for ($i = 0; $i < $num; $i++) {
            $tid = mysql_result($result, $i, 'tid');
            $title = mysql_result($result, $i, 'title');
            
            echo"<tr><td><a href='trip_view.php?tid=$tid'> $title</a></td>
            <td><a href='all_trips.php?delete_id=$tid'><input type='submit' value='Delete' class='submit'></a></td>
            </tr>";
        }
?>
    </table>
    </div>
    </div>
    <div class="right">

        <h3>Most Popular Trips:</h3>
        <?php echo(popularTrips()); ?>

        <h3>Recently Shared Trips:</h3>
        <?php echo(recentTrips()); ?>
    </div>
        <div class="footer">
        <p><a href="contact.php">Contact</a> | <a href="#">Accessibility</a> | <a href="#">Disclaimer</a><br/>
            &copy; Copyright 2010 TripShare</p>
    </div>
    
</div>
</body>
</html>
 
