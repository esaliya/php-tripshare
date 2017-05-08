<?php
/**
 * Created by PhpStorm.
 * User: saliya
 */

$authUser = isset($_SESSION['authUser']) ? $_SESSION['authUser'] : NULL;
$authRole = isset($_SESSION['authRole']) ? $_SESSION['authRole'] : 'user';


$pages = array(
    "Home" => array("link" => "index.php", "user" => "any", "role" => "any"),
    "Search" => array("link"=> "search.php","user"=>"any","role"=>"any"),
    "Profile" => array("link"=>"profile_info.php","user"=>"member", "role"=>"user"),
    "New Trip" => array("link"=>"trip_design.php","user"=>"member", "role"=>"user"),
    "My Trips" => array("link"=>"my_trips.php","user"=>"member", "role"=>"user"),
    "Pinned Trips" => array("link"=>"pinned_trips.php","user"=>"member", "role"=>"user"),
    "Users" => array("link"=>"users.php","user"=>"member", "role"=>"admin"),
    "Trips" => array("link"=>"all_trips.php","user"=>"member", "role"=>"admin"));

function genNavi($active_page) {
    global $pages, $authUser, $authRole;
    $ul = "<ul>";
    foreach($pages as $page=>$prop) {
        if ($prop['user'] == "member") {
            if (is_null($authUser)) {
                continue;
            }
            if ($authRole != "admin" && $prop['role'] != $authRole) {
                continue;
            }
        }
        if ($active_page == $page) {
            $ul .= "<li class=\"active\">" . $page . "</li>";
        } else {
            $ul .= "<li><a href=\"" . $prop["link"] . "\">" . $page . "</a></li>";
        }
    }
    $ul .= "</ul>";
    return $ul;
}

function genTopLinks() {
    global $authUser;
    if (is_null($authUser)) {

        return 'You are not signed in. [<a href="signin.php">Sign-in</a>] or [<a href="register.php">Register</a>]';
    } else {
        $fname = $_SESSION['authFname'];
        $lname = $_SESSION['authLname'];
        return 'You are logged in as ' .  $fname .  ' ' . $lname . ' [<a href="logout.php">Logout</a>]';
    }
}

function recentTrips() {
    dbConnect();
    $qry = query("select distinct t.title as title,t.tid as tid,t.description as description,p.disk_location as disk_location   from trips t,photos p where p.tid=t.tid order by t.posted_date desc LIMIT 0,3");

    $div ="";
    while ($row = mysql_fetch_array($qry))
    {
        $tid1 = $row['tid'];
        $div .= "<div class=\"right_articles\"><p>";
        $div .= "<img src=\"" . $row['disk_location'] . "/" . "thumb.gif\" alt=\"Image\" title=\"thumb\" class=\"image\"/>";
        if(strlen($row['description']) < 60) {//read more option
            $div .= "<b>" . $row['title'] . "</b><br/>" . $row['description'];
            $div .= "<a href='trip_view.php?tid=$tid1'> Read More </a></p></div>";
        }
        else {//cut off string and read more option
            $subDescription = substr($row['description'], 0, 60);
            $div .= "<b>" . $row['title'] . "</b><br/>" . $subDescription;
            $div .= "<a href='trip_view.php?tid=$tid1'> Read More </a></p></div>";
        }
    }
    return $div;
}

function popularTrips() {
    dbConnect();
    $qry1 = query("select distinct X.avg_rating as avg_rating , X.tid as tid, t.title as title , t.description as description , p.disk_location as disk_location from
(select r.tid as tid,avg(r.rating) as avg_rating
from ratings r group by r.tid
) X ,trips t,photos p
where X.tid = t.tid and
t.tid = p.tid
order by X.avg_rating desc
LIMIT 0,2");

    $div1 ="";

    while ($row1 = mysql_fetch_array($qry1))
    {
        $tid2 = $row1['tid'];
        $div1 .= "<div class=\"right_articles\"><p>";
        $div1 .= "<img src=\"" . $row1['disk_location']."/" ."thumb.gif\" alt=\"Image\" title=\"thumb\" class=\"image\"/>";

        if(strlen($row1['description']) < 60)
        {//read more option
            $div1 .= "<b>" . $row1['title'] . "</b><br/>" . $row1['description'];
            $div1 .= "<a href='trip_view.php?tid=$tid2'> Read More </a></p></div>";
        }
        else
        {//cut off string and read more option
            $subDescription = substr($row1['description'], 0, 60);
            $div1 .= "<b>" . $row1['title'] . "</b><br/>" . $subDescription;
            $div1 .= "<a href='trip_view.php?tid=$tid2'> Read More </a></p></div>";
        }
    }
    return $div1;
}
?>
