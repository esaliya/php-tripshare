<?php
session_start();
include('links.php');
require('db_model.php');
require('db_access.php');
$uid;
$trip_id;
$role;
if(isset($_SESSION["authUser"])) {
    global $uid,$role;
  $uid = $_SESSION["authUser"];
  $role = $_SESSION["authRole"];

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
            <h1><a href="index.php" title="TrpShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>
    </div>

    <div class="bar" id="navi">
    <!--Navigation Bar-->
    <?php echo genNavi("Search"); ?>

    </div>
    <div class="left">

<?php

if(isset($_POST['submit'])) {
addToMyPinnedTrips();
}
else
{

global $trip_id;
$trip_id=$_GET['tid'];
dbConnect();
$trip = Trip::fromDB($trip_id);
$tripAttractions=$trip->getAttractions();
$tripRestaurants=$trip->getRestaurants();
$avoids=$trip->getAvoids();
$cost=$trip->getCost();
$hotels=$trip->getHotels();
$tripDestinations = $trip->getTripDestinations();
$date=$trip->getDate();
$description=$trip->getDescription();
$transports=$trip->getTransports();
$title=$trip->getTitle();
$userID=$trip->getUid();
if(isset($_POST['RateIt']))
    {
     $rating;
    $selected_radio = $_POST['r1'];

    if ($selected_radio =='1') {
    $rating=1;
    }
    else if ($selected_radio == '2') {
    $rating= 2;
    }
    else if ($selected_radio == '3') {
    $rating = 3;
    }
    else if ($selected_radio == '4') {
    $rating = 4;
    }
    else if ($selected_radio == '5') {
    $rating = 5;
    }
    rateTrips($rating);

    }
if(isset($_POST['ViewPhotos']))
{
    echo "<br /><br />";
    $query="Select disk_location from photos where tid='".$trip_id."'";
    $result=query($query);
    if(is_resource($result))
    {
    $row=mysql_fetch_array($result);
    $dir=$row['disk_location'];
    if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
    //    echo "<table>";
        while (($file = readdir($dh)) !== false) {

          //  echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
            if(filetype($dir."/" . $file)!='dir')
            {
                echo("<img src=\" ".$dir."/".$file."\">");
                echo ("&nbsp;&nbsp;&nbsp;");

            }
        }

        closedir($dh);
    }
    }
    }
    else
    {
        echo("<p>There are no photos for the trip. </p>");
    }
}

if(!isset($_POST['ViewPhotos']))
{
   echo("<br><p><b><h3> Title : ".$trip->getTitle()."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Posted By : ");
    $userName=getUserDetails($trip->getUid());
    $tripUID=$trip->getUid();
    echo("<a href='profile_info.php?uid=".$tripUID."'>".$userName."</h3> </a></p>");

   echo("<p><h3>Description</h3><br>");
    echo("<p>".$trip->getDescription()."</p>");
    echo("<p><h3>Cost</h3><br>");
    echo("<p>".$trip->getCost()."</p>");
    printTripViewInformation('Attraction',$tripAttractions);
    printTripViewInformation('Restaurants',$tripRestaurants);
    printTripViewInformation('Avoids',$avoids);
    printTripViewInformation('Hotels',$hotels);
    printTripViewInformation('Transport',$transports);
    if(isset($uid))
    {
    echo("<form action='trip_view.php?tid=".$trip_id ."' method='post'><input type='submit' value='Pin This!' name='submit' class='submit'></form>");
    }
}
}
    function printTripViewInformation($tripInformationName,$tripInformationArray)
    {
        echo("<p><h3>".$tripInformationName."</h3><br>");
        foreach($tripInformationArray as $tripInfo=>$tripInformationArray)
        {
            if(method_exists($tripInformationArray,'getName'))
            {
            echo("<p><b> Name     : </b> ".$tripInformationArray->getName()."</p>");
            }
            if(method_exists($tripInformationArray,'getType'))
            {
            echo("<p><b> Type     : </b>".$tripInformationArray->getType()."</p>");
            }
            if(method_exists($tripInformationArray,'getStars'))
            {
            echo("<p><b> Rating     : </b>".$tripInformationArray->getStars()." Star</p>");
            }
            if(method_exists($tripInformationArray,'getAvgCost'))
            {
                echo("<p><b> Average Cost     : </b>".$tripInformationArray->getAvgCost()."</p>");
            }
            if(method_exists($tripInformationArray,'getStreetAddress')|| method_exists($tripInformationArray,'getCity')||method_exists($tripInformationArray,'getState')||method_exists($tripInformationArray,'getCountry'))
            {
                echo("<p><b> Location : </b>");
                if(method_exists($tripInformationArray,'getStreetAddress'))
                {
                echo("".$tripInformationArray->getStreetAddress().",");
                }
                if(method_exists($tripInformationArray,'getCity'))
                {
                 echo($tripInformationArray->getCity()." , ");
                }
                if(method_exists($tripInformationArray,'getState'))
                {
                    echo($tripInformationArray->getState()." , ");
                }
                if(method_exists($tripInformationArray,'getCountry'))
                {
                    echo($tripInformationArray->getCountry()."</p> ");
                }
            }
            if(method_exists($tripInformationArray,'getComments'))
            {
            echo("<p><b> Comments : </b>".$tripInformationArray->getComments()."</p>");
            }
            if(method_exists($tripInformationArray,'getReason'))
            {
            echo("<p><b> Reason : </b>".$tripInformationArray->getReason()."</p>");
            }
            if(method_exists($tripInformationArray,'getTime'))
            {
            echo("<p><b> Time : </b>".$tripInformationArray->getTime()."</p>");
            }
            if(method_exists($tripInformationArray,'getURL')||method_exists($tripInformationArray,'getEmail')||method_exists($tripInformationArray,'getPhoneNumber'))
            {
            echo("<p><b> Contact Information :     </b>");
            if(method_exists($tripInformationArray,'getURL')){
            echo("<br><b>Website: " . $tripInformationArray->getURL()."</br>");}
            if(method_exists($tripInformationArray,'getEmail'))
            {
            echo("<b>Email: " . $tripInformationArray->getEmail()."</br>");
            }
            if(method_exists($tripInformationArray,'getTelephone'))
            {
            echo("<b>Telephone: " . $tripInformationArray->getTelephone()."</br></p>");
            }
            }
            
        }


    }

function addToMyPinnedTrips()
{
    global $uid;
    $trip_id=$_GET['tid'];
    dbConnect();
    $query = "INSERT INTO pinned_trips( uid,tid) VALUES ('$uid','$trip_id')";
    $result=query($query);
    closeDB();
    echo("Pinned trip successfully!!!!");
 }

function rateTrips($rating)
{

    global $uid;
    $trip_id=$_GET['tid'];
    dbConnect();
    $query="select tid,uid from ratings where uid='".$uid."' AND tid='".$trip_id."'";
    $result=query($query);
    if(is_resource($result))
    {
        $query="delete from ratings where uid='".$uid."' AND tid='".$trip_id."'";
        query($query);
     }
    $query = "INSERT INTO ratings( uid,tid,rating) VALUES ('$uid','$trip_id',".$rating.")";
    $result=query($query);
    closeDB();


}

    function getUserDetails($userID)
    {
        $query="select fname, lname from members where uid='".$userID."'";
        $result=query($query);
        $row=mysql_fetch_array($result);
        $fname=$row["fname"];
        $lname=$row["lname"];
        return $fname." ".$lname;
    }
 ?>
<?php if(isset($uid)&& !(isset($_POST['submit']))&&!(isset($_POST['ViewPhotos']))): ?>

<div id="rating">
<form action='<?php echo"trip_view.php?tid=$trip_id" ?>' method = POST>
<br><input type='submit' value='Rate It!' name='RateIt' class='submit'>
<input type="radio" value="1" id="id0" name="r1" />&nbsp; 1 &nbsp;
<input type="radio" value="2" id="id1" name="r1" />&nbsp; 2 &nbsp;
<input type="radio" value="3" id="id2" name="r1" />&nbsp; 3 &nbsp;
<input type="radio" value="4" id="id3" name="r1" />&nbsp; 4 &nbsp;
<input type="radio" value="5" id="id4" name="r1" />&nbsp; 5 &nbsp;


 </form>
    <br /><br />
    <div>
<?php endif; ?>

<?php if(!(isset($_POST['ViewPhotos']))): ?>
<form action='<?php echo"trip_view.php?tid=$trip_id" ?>' method = POST>
<br><input type='submit' value='View Photos' name='ViewPhotos' class='submit'>
<?php endif; ?>

<br><A href="javascript: history.back();">Back</A>
</body>
</html>