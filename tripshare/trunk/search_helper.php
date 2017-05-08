
<?php
session_start();
include("links.php");
include("db_access.php");
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
          
    
     <p>&nbsp;</p>
    </div>

    <div class="left">
        <h3>Search Results:</h3>
    </div>
    <div class="right">

        <h3>Most Popular Trips:</h3>
        <?php echo(popularTrips()); ?>

        <h3>Recently Shared Trips:</h3>
        <?php echo(recentTrips()); ?>
    </div>

<?php

if (isset($_POST['submit'])) {
    if(isset($_POST['userName']))
    {
    $userName = $_POST['userName'];
    }
    else
    {
        $userName=null;
    }
    if(isset($_POST['destination']))
    {
    $destination = $_POST['destination'];
     }
    else
    {
       $destination=null;
    }
    if(isset($_POST['priceRange']))
    {
    $priceRange = $_POST['priceRange'];

    }
    else
    {
        $priceRange=null;
    }
    if(isset($_POST['rating']))
    {
    $rating = $_POST['rating'];
    }
    else
    {
        $rating=null;
    }
    if(isset($_POST['attraction']))
    {
    $attraction=$_POST['attraction'];
    }
    else
    {
        $attraction=null;
    }

    if(isset($_POST['simpleSearchCity']))
    {
    $simpleSearchCity=$_POST['simpleSearchCity'];
    }
    else
    {
        $simpleSearchCity=null;
    }

    $fromList="trips T";
    $whereList="";


    if(strlen($userName)>0)
    {
        $fromList.=",members M";
        $name=preg_split('/ /',$userName);
        $firstName=$name[0];
        if(count($name)>1)
        {
        $lastName=$name[1];
        $whereList.=" T.uid = M.uid AND M.fname='".$firstName."' AND M.lname='".$lastName."'";
        }
        else
        {
            $whereList.=" T.uid = M.uid AND M.fname='".$firstName."'";
        }



    }
    if(strlen($destination)>0)
    {
        if(strlen($whereList)>0)
        {
            $whereList.=" AND D.tid=T.tid AND ";
        }
        else
        {
            $whereList.="D.tid=T.tid AND ";
        }
       $destinationList="";
        $multipleDestinations=preg_split("/;/",$destination);

        if(count($multipleDestinations)>0)
        {
            $whereList.="(";
        }
        for($destinationCount=0;$destinationCount<count($multipleDestinations);$destinationCount++)
        {
            $destinationDetails=preg_split("/,/",$multipleDestinations[$destinationCount]);
            if($destinationCount==0)
            {
            switch(count($destinationDetails))
            {
                case 0:
                    echo("<p> No destination details</p>");
                    break;
                case 1:
                    $whereList.="( D.city='".$destinationDetails[0]."')";
                    break;
                case 2:
                    $whereList.="( D.city='".$destinationDetails[0]."' AND (D.state='".$destinationDetails[1]."' OR D.province='".$destinationDetails[1]."'))";
                    break;
                case 3:
                    $whereList.=" (D.city='".$destinationDetails[0]."' AND (D.state='".$destinationDetails[1]."' OR D.province='".$destinationDetails[1]."') AND D.country='".$destinationDetails[2]."')";
                    break;

            }
            }
            else
            {
                $whereList.=" OR ";
                switch(count($destinationDetails))
            {
                case 0:
                    echo("<p> No destination details</p>");
                    break;
                case 1:
                    $whereList.="( D.city='".$destinationDetails[0]."')";
                    break;
                case 2:
                    $whereList.="( D.city='".$destinationDetails[0]."' AND (D.state='".$destinationDetails[1]."' OR D.province='".$destinationDetails[1]."'))";
                    break;
                case 3:
                    $whereList.=" (D.city='".$destinationDetails[0]."' AND (D.state='".$destinationDetails[1]."' OR D.province='".$destinationDetails[1]."') AND D.country='".$destinationDetails[2]."')";
                    break;

            }

            }


        }

       if(count($multipleDestinations)>0)
        {
            $whereList.=")";
        }

        $fromList.=",trip_destinations D ";


    }

    if(strlen($priceRange)>0)
        {
            if(strlen($whereList)>0)
            {
                $whereList.=" AND";
            }
            $whereList.=" T.cost <= ".$priceRange;

        }


    if(strlen($rating)>0)
    {
        if(strlen($whereList)>0)
        {
            $whereList.=" AND";
        }
        $fromList.=",ratings R";
        $whereList.=" T.tid =R.tid AND R.rating= ".$rating;

    }

    if(strlen($simpleSearchCity)>0)
    {
        $fromList.=",trip_Destinations D";
        $whereList.=" T.tid =D.tid AND D.city= '".$simpleSearchCity."'";

    }

    if(strlen($attraction)>0)
    {
        if(strlen($whereList)>0)
        {
            $whereList.=" AND";
        }
        $fromList.=" , attractions A";
        $whereList.=" T.tid =A.tid AND A.type='".$attraction."'";

    }
    $query = "SELECT distinct T.tid FROM ".$fromList."  WHERE ".$whereList;
    dbConnect();
     $result = query($query);
     closeDB();
      $i=1;

      if(is_resource($result)>0)
      {
     while ($row = mysql_fetch_array($GLOBALS['result'])) {
         echo("<div class=\"left\">");
         echo("<div class=\"left_box\">");
         echo("<br><a href='trip_view.php?tid=" . $row["tid"] . "'>Link to trip ".$i."</a>");
         $i++;

    }
    }
    else
    {
         echo("<b><p>No results found!</p></b>");
    }
    echo("<br><A href=\"javascript: history.back();\">Back</A>");

}
else {



    echo  "<p>Please enter a search query</p>";
    echo("<br><A href=\"javascript: history.back();\">Back</A>");
}

?>



</div>
<div class="footer">
    <p><a href="contact.php">Contact</a> <br/>
        &copy; Copyright 2010 TripShare</p>
</div>

</body>
</html>