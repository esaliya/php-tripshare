<?php
//require('db_access.php');
//dbConnect();
function recentTrips() {

    $qry = mysql_query("select t.title as title,t.description as description,p.disk_location as disk_location   from trips t,photos p where p.tid=t.tid order by t.posted_date desc LIMIT 0,3");

    while ($row = mysql_fetch_array($qry))
    {

        echo "<p><img src=" . $row['disk_location'] . " alt='Image' title='Image' class='image'/><b>";
        echo "<p><b>" . $row['title'] . "</b><br/>" . $row['description'] . "</p></br>";


    }
}

function popularTrips() {

    $qry1 = mysql_query("select distinct X.avg_rating as avg_rating , X.tid , t.title as title , t.description as description , p.disk_location as disk_location
from
(select r.tid as tid,avg(r.rating) as avg_rating
from ratings r group by r.tid
) X ,trips t,photos p
where X.tid = t.tid and
t.tid = p.tid
order by X.avg_rating desc
LIMIT 0,2");

    while ($row1 = mysql_fetch_array($qry1))
    {
       // echo "<p><img src='C:\wamp\misc.jpg' alt='Image' title='Image' class='image'/><b>";
        echo "<p><img src=" . $row1['disk_location'] . " alt='Image' title='Image' class='image'/><b>";
        echo "<p><b>" . $row1['title'] . "</b><br/>" . $row1['description'] . "</p></br>";

    }
}

?>
