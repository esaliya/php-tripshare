<?php
// todo: Ravi

// Include links.php
session_start();
require('links.php');
require('db_access.php');
//require('function.php');
dbConnect();

?>

<!--test code for navigation by Saliya-->

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
            <br/>
        </div>
        <div class="logo">
            <h1><a href="index.php" title="TrpShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>
    </div>


    <div class="bar" id="navi">
        <!--Navigation Bar-->
    <?php echo genNavi("Home"); ?>
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
        <h3>How TripShare Works:</h3><br>
        <p class="italic" align="justify"><b>Travel is an essential part of human life and may take different forms depending on the occasion.
        Trips, however, are undoubtedly the only form that is loved and wished to do often by almost everybody. In fact people
        spend a considerable amount of time on planning trips. It is essential to research on a trip
        before traveling primarily for two reasons, i.e. finance and risk. Every trip involves some amount of financial
        expense and it is usually larger than daily expenses. Also, most of the time people travel to places that they have
        not visited before, which may impose potential hazards on them. Therefore assessing the trip for its value and risk
        is critical prior to traveling. This is when the experience of a previous traveller becomes more than just helpful.<b></b></p><br><br><br>
        
        <div class="left_articles">
        <p><img src="images/travel.gif" alt="Image" title="Image" class="image"/><b>Travel Anywhere</b><br></p>
        </div>
        <br><br><br>
            
        <div class="left_articles">
        <p><img src="images/write.gif" alt="Image" title="Image" class="image"/><b>Post Trips</b><br/>
        </div>
        <br><br><br>

        <div class="left_articles">
        <p><img src="images/photos.gif" alt="Image" title="Image" class="image"/><b>Publish your photos. </b><br/>
        </p>
        </div>
        
            
        
    </div>
    
    <div class="right">

        <h3>Most Popular Trips:</h3>
        <?php echo(popularTrips()); ?>

        <h3>Recently Shared Trips:</h3>
        <?php echo(recentTrips()); ?>
    </div>
    <div class="footer">
        <p><a href="contact.php">Contact</a> <br/>
            &copy; Copyright 2010 TripShare</p>
    </div>

</div>
</body>
</html>