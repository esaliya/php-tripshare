<?php
session_start();
include('links.php');

if (!isset($_SESSION['errorid'])) {
    header("Location: index.php");
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
            <h1><a href="#" title="TripShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>
    </div>

    <div class="bar" id="navi">
    <!--Navigation Bar-->
    <?php echo genNavi("Error"); ?>
    </div>

    <!--    todo: style this-->
    <?php echo ($_SESSION['errorid']); unset($_SESSION['errorid']); ?>

</div>
</body>
</html>

