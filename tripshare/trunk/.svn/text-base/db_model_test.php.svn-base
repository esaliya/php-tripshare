<?php
///**
// * Created by PhpStorm.
// * User: saliya
// */
//
//session_start();
//require('db_access.php');
//require('db_model.php');
//
//$msg = urldecode("Oops! Something went wrong. Try again in few seconds.");
//
//if (!dbConnect()) {
//    header("Location: error.php?msg=$msg");
//}
//$r = NULL;
//if (isset($_REQUEST['action'])) {
//    $action = $_REQUEST['action'];
//    if ("add" == $action) {
//        $r = new Restaurant('1', "usa", "in", "n/a", "bloomington",
//            $_POST['name'], $_POST['type'], $_POST['comments'], $_POST['avg_cost'],
//            $_POST['street_address'], $_POST['telephone'], $_POST['email'], $_POST['url']);
//        if (!$r->toDB()) {
//            header("Location: error.php?msg=$msg");
//        }
//    } else if ("edit" == $action) {
//        $rid = $_POST['rid'];
//
//        $r = Restaurant::fromDB($rid);
//
//    }
//}
//
//
//
//
//
//
//?>
<!---->
<!--<html>-->
<!--<body>-->
<!--<form action="db_model_test.php?action=add" id="addRestaurantFrm" method="post">-->
<!--    <table cellspacing="2">-->
<!--        <tbody>-->
<!--        <tr>-->
<!--            <td>Name:</td>-->
<!--            <td><label>-->
<!--                <input type="text" name="name" value="--><?php //if ($r) {echo $r->getName();} ?><!--"/>-->
<!--            </label></td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>Type:</td>-->
<!--            <td><label>-->
<!--                <select name="type" >-->
<!--                    <option>American</option>-->
<!--                    <option>Vegeterian</option>-->
<!--                    <option>Non Veg</option>-->
<!--                    <option>Chineese</option>-->
<!--                    <option>Sri Lankan</option>-->
<!--                    <option>Italian</option>-->
<!--                    <option>Mexican</option>-->
<!--                    <option>Indian</option>-->
<!--                </select>-->
<!--            </label></td>-->
<!---->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>Comments:</td>-->
<!--            <td><label>-->
<!--                <textarea cols="40" rows="10" name="comments">--><?php //if ($r) {echo $r->getComments();} ?><!--</textarea>-->
<!--            </label></td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>Average Cost ($):</td>-->
<!--            <td><label>-->
<!--                <input type="text" name="avg_cost" value="--><?php //if ($r) {echo $r->getAvgCost();} ?><!--"/>-->
<!--            </label>-->
<!---->
<!--            </td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>Street Address:</td>-->
<!--            <td><label>-->
<!--                <input type="text" name="street_address" value="--><?php //if ($r) {echo $r->getStreetAddress();} ?><!--"/>-->
<!--            </label></td>-->
<!--        </tr>-->
<!---->
<!--        <tr>-->
<!--            <td>Phone:</td>-->
<!--            <td><label>-->
<!--                <input type="text" name="telephone" value="--><?php //if ($r) {echo $r->getTelephone();} ?><!--"/>-->
<!--            </label></td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>Email:</td>-->
<!--            <td><label>-->
<!--                <input type="text" name="email" value="--><?php //if ($r) {echo $r->getEmail();} ?><!--"/>-->
<!--            </label></td>-->
<!--        </tr>-->
<!--        <tr></tr>-->
<!--        <tr>-->
<!--            <td>Web Site:</td>-->
<!--            <td><label>-->
<!--                <input type="text" name="url" value="--><?php //if ($r) {echo $r->getUrl();} ?><!--"/>-->
<!--            </label></td>-->
<!---->
<!--        </tr>-->
<!--        </tbody>-->
<!--    </table>-->
<!--    <input name="add" type="submit" value="Add"/>-->
<!--</form>-->
<!---->
<!--<form action="db_model_test.php?action=edit" method="post" id="editRestaurantFrm">-->
<!--    <label><input name="rid" type="text"/></label>-->
<!--    <label><input name="edit" type="submit" value="Edit"/></label>-->
<!--</form>-->
<!---->
<?php
//if ($r) {
//    echo($r->getTid());
//    echo("<br>");
//    echo($r->getCountry());
//    echo("<br>");
//    echo($r->getState());
//    echo("<br>");
//    echo($r->getProvince());
//    echo("<br>");
//    echo($r->getRid());
//}
//?>
<!---->
<!---->
<!---->
<!--</body>-->
<!--</html>-->
<!-- -->

<?php
require('db_model.php');
session_start();
require('db_access.php');

dbConnect();

$trip = Trip::fromDB(4);
//echo ('came here');
if (is_null($trip)) {
    echo('null');
}

$tripDestinations = $trip->getTripDestinations();
$key="sri lanka,n/a,central,kandy";
$tripDestination = $tripDestinations[$key];
echo($tripDestination->getDescription());
unset($tripDestinations[$key]);
$tripDestinationsAgain = &$trip->getTripDestinations();
$tripDestinationAgain = $tripDestinationsAgain[$key];
echo($tripDestinationAgain->getDescription());

$h = new Hotel("adf",4,"ad",45,"adf","6789","adf","hjk");
$h->setStars(5);

//$jsonTrip = json_encode($trip);
//echo($jsonTrip);
//$tripDestinations = $trip->getTripDestinations();
//foreach($tripDestinations as $destination=>$tripDestination) {
//    echo("<br>" . $destination . "</br>");
//    echo("<br>" . $tripDestination->getDescription() . "</br>");
//}
?>
