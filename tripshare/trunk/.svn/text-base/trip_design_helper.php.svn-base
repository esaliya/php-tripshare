<?php
/**
 * Created by PhpStorm.
 * User: saliya
 */
require('db_model.php');
session_start();
require('db_access.php');

if (!isset($_SESSION['authUser'])) {
    // Not authroized. So go back to home page.
    header("Location:index.php");
    exit();
}
$authUser = $_SESSION['authUser'];
$trip = $_SESSION['trip'];
if (!$trip) {
    // Bad request.
    header("Location:index.php");
    exit();
}

// This is hardly possible when logout works properly.
if ($trip->getUid() != $authUser) {
    // Trying to cheat eh?
    header("Location:index.php");
    exit();
}

if (isset($_REQUEST['cancel'])) {
    handleCancel();
}

// Todo: test submit
if(isset($_POST['testsubmit'])) {
    dbConnect();
    $trip->toDB();
    unset($_SESSION['trip']);
    unset($_SESSION['pageId']);
    header("Location:my_trips.php");
    exit();
}

$pageId = isset($_POST['pageId']) ? $_POST['pageId'] : NULL;
if ($pageId >= 0) {
    switch($pageId) {
        case 0:
            handlePage0($trip);
            break;
        case 1:
            handlePage1($trip);
            break;
        case 2:
            handlePage2($trip);
            break;
        case 3:
            handlePage3($trip);
            break;
        default:
            // Bad request.
            header("Location:index.php");
            exit();
    }

} else {
    // Bad request.
    header("Location:index.php");
    exit();
}

function handlePage3($trip) {
    $action = $_POST['page3Action'];
    if ("update" == $action || "delete" == $action) {
        $inDB = $_POST['page3InDb']; // inDB should be present in POST now
        $hid = $_POST['page3Hid']; // page3Hid should be present for an edit.
        $hotels = &$trip->getHotels();
        $h = $hotels[$hid];
        if ($inDB) {
            // Inform the action to be used when finally calling its toDB()
            $h->setAction($action);
            if ("update" == $action) {
                // Just update values
                $destArr = explode(',',$_POST['page3DestKey']);
                $h->setCountry($destArr[0]);
                $h->setState($destArr[1]);
                $h->setProvince($destArr[2]);
                $h->setCity($destArr[3]);
                $h->setName($_POST['name']);
                $h->setStars($_POST['starSelect']);
                $h->setComments($_POST['comments']);
                $h->setAvgCost($_POST['avgCost']);
                $h->setStreetAddress($_POST['streetAddress']);
                $h->setTelephone($_POST['telephone']);
                $h->setEmail($_POST['email']);
                $h->setUrl($_POST['url']);
            } else if ("delete" == $action){
                // mark as deleted;
                $h->setDeleted(TRUE);
            }
        } else {
            // Good! This was not in DB.
            if ("update" == $action) {
                // This is still action "add" for the DB as this is not in DB yet.
                $h->setAction("add");

                // Just update values
                $destArr = explode(',',$_POST['page3DestKey']);
                $h->setCountry($destArr[0]);
                $h->setState($destArr[1]);
                $h->setProvince($destArr[2]);
                $h->setCity($destArr[3]);
                $h->setName($_POST['name']);
                $h->setStars($_POST['starSelect']);
                $h->setComments($_POST['comments']);
                $h->setAvgCost($_POST['avgCost']);
                $h->setStreetAddress($_POST['streetAddress']);
                $h->setTelephone($_POST['telephone']);
                $h->setEmail($_POST['email']);
                $h->setUrl($_POST['url']);
            } else if ("delete" == $action) {
                // This is not in DB yet, so just unset the association
                unset($hotels[$h->getHid()]);
            }
        }
        $_SESSION['pageId'] = 'page' . $_POST['pageId'];
    } else if ("add" == $action || "addAndNext" == $action) {
        // OK, going to add a new restaurant.
        $h = new Hotel($_POST['name'],$_POST['starSelect'],$_POST['comments'],$_POST['avgCost'],
            $_POST['streetAddress'],$_POST['telephone'],$_POST['email'],$_POST['url']);
        $h->setAction("add");
        $destArr = explode(',',$_POST['page3DestKey']);
        $h->setCountry($destArr[0]);
        $h->setState($destArr[1]);
        $h->setProvince($destArr[2]);
        $h->setCity($destArr[3]);
        // When adding a new X give some uniqid from php just for the sake of UI. but make sure
        // the action is marked as add so that it will get inserted to DB as a new one.
        // Set some uniqid from php just for the sake of UI
        $h->setHid(uniqid());

        $hotels = &$trip->getHotels();
        // inDB and deleted should be FALSE by default. So just add a new association in restaurants.
        $hotels[$h->gethid()] = $h;
        if ("addAndNext" == $action) {
            $_SESSION['pageId'] = 'page' . ($_POST['pageId'] + 1);
        } else {
            $_SESSION['pageId'] = 'page' . $_POST['pageId'];
        }
    } else if ("nextOnly" == $action) {
        $_SESSION['pageId'] = 'page' . ($_POST['pageId'] + 1);
    }
    header("Location:trip_design.php?status=pending");
}

function handlePage2($trip) {
    $action = $_POST['page2Action'];
    if ("update" == $action || "delete" == $action) {
        $inDB = $_POST['page2InDb']; // inDB should be present in POST now
        $rid = $_POST['page2Rid']; // page2Rid should be present for an edit.
        $restaurants = &$trip->getRestaurants();
        $r = $restaurants[$rid];
        if ($inDB) {
            // Inform the action to be used when finally calling its toDB()
            $r->setAction($action);
            if ("update" == $action) {
                // Just update values
                $destArr = explode(',',$_POST['page2DestKey']);
                $r->setCountry($destArr[0]);
                $r->setState($destArr[1]);
                $r->setProvince($destArr[2]);
                $r->setCity($destArr[3]);
                $r->setName($_POST['name']);
                $r->setType($_POST['typeSelect']);
                $r->setComments($_POST['comments']);
                $r->setAvgCost($_POST['avgCost']);
                $r->setStreetAddress($_POST['streetAddress']);
                $r->setTelephone($_POST['telephone']);
                $r->setEmail($_POST['email']);
                $r->setUrl($_POST['url']);
            } else if ("delete" == $action){
                // mark as deleted;
                $r->setDeleted(TRUE);
            }
        } else {
            // Good! This was not in DB.
            if ("update" == $action) {
                // This is still action "add" for the DB as this is not in DB yet.
                $r->setAction("add");

                // Just update values
                $destArr = explode(',',$_POST['page2DestKey']);
                $r->setCountry($destArr[0]);
                $r->setState($destArr[1]);
                $r->setProvince($destArr[2]);
                $r->setCity($destArr[3]);
                $r->setName($_POST['name']);
                $r->setType($_POST['typeSelect']);
                $r->setComments($_POST['comments']);
                $r->setAvgCost($_POST['avgCost']);
                $r->setStreetAddress($_POST['streetAddress']);
                $r->setTelephone($_POST['telephone']);
                $r->setEmail($_POST['email']);
                $r->setUrl($_POST['url']);
            } else if ("delete" == $action) {
                // This is not in DB yet, so just unset the association
                unset($restaurants[$r->getRid()]);
            }
        }
        $_SESSION['pageId'] = 'page' . $_POST['pageId'];
    } else if ("add" == $action || "addAndNext" == $action) {
        // OK, going to add a new restaurant.
        $r = new Restaurant($_POST['name'],$_POST['typeSelect'],$_POST['comments'],$_POST['avgCost'],
            $_POST['streetAddress'],$_POST['telephone'],$_POST['email'],$_POST['url']);
        $r->setAction("add");
        $destArr = explode(',',$_POST['page2DestKey']);
        $r->setCountry($destArr[0]);
        $r->setState($destArr[1]);
        $r->setProvince($destArr[2]);
        $r->setCity($destArr[3]);
        // When adding a new X give some uniqid from php just for the sake of UI. but make sure
        // the action is marked as add so that it will get inserted to DB as a new one.
        // Set some uniqid from php just for the sake of UI
        $r->setRid(uniqid());

        $restaurants = &$trip->getRestaurants();
        // inDB and deleted should be FALSE by default. So just add a new association in restaurants.
        $restaurants[$r->getRid()] = $r;
        if ("addAndNext" == $action) {
            $_SESSION['pageId'] = 'page' . ($_POST['pageId'] + 1);
        } else {
            $_SESSION['pageId'] = 'page' . $_POST['pageId'];
        }
    } else if ("nextOnly" == $action) {
        $_SESSION['pageId'] = 'page' . ($_POST['pageId'] + 1);
    }
    header("Location:trip_design.php?status=pending");
}

function handlePage1($trip) {
    $action = $_POST['page1Action'];
    if ("update" == $action || "delete" == $action) {
        $inDB = $_POST['page1InDb'];
        $destKey = $_POST['page1DestKey'];
        $tripDestinations = &$trip->getTripDestinations();
        $tripDestination = $tripDestinations[$destKey];
        if($inDB) {
            // Inform the action to be used when finally calling its toDB()
            $tripDestination->setAction($action);
            if ("update" == $action) {
                $country = $_POST['country'];
                $city = $_POST['city'];
                $description = $_POST['description'];

                $state = NULL;
                $province = NULL;
                if ($_POST['stateProvinceOp'] == "province") {
                    $province = $_POST['stateProvince'];
                    $state = 'n/a';
                } else if ($_POST['stateProvinceOp'] == "state") {
                    $state = $_POST['stateProvince'];
                    $province = 'n/a';
                }

                updateX($trip->getRestaurants(), $country, $state, $province, $city, $tripDestination);
                updateX($trip->getHotels(), $country, $state, $province, $city, $tripDestination);
                updateX($trip->getAttractions(), $country, $state, $province, $city, $tripDestination);
                updateX($trip->getTransports(), $country, $state, $province, $city, $tripDestination);
                updateX($trip->getAvoids(), $country, $state, $province, $city, $tripDestination);

                $tripDestination->setDestination($country, $state, $province, $city);
                $tripDestination->setDescription($description);

            } else if ("delete" == $action) {
                $tripDestination->setDeleted(TRUE);
                deleteX($trip->getRestaurants(), $tripDestination);
                deleteX($trip->getHotels(), $tripDestination);
                deleteX($trip->getAttractions(), $tripDestination);
                deleteX($trip->getTransports(), $tripDestination);
                deleteX($trip->getAvoids(), $tripDestination);
            }
        } else {
            // Good! This was not in DB.
            if ("update" == $action) {
                // There can't be any X that depends on this destination since we don't allow "back" in wiz.
                // Also, this is still action "add" for the DB as this is not in DB yet.
                $tripDestination->setAction("add");

                $country = $_POST['country'];
                $city = $_POST['city'];
                $description = $_POST['description'];

                $state = NULL;
                $province = NULL;
                if ($_POST['stateProvinceOp'] == "province") {
                    $province = $_POST['stateProvince'];
                    $state = 'n/a';
                } else if ($_POST['stateProvinceOp'] == "state") {
                    $state = $_POST['stateProvince'];
                    $province = 'n/a';
                }

                $tripDestination->setDestination($country, $state, $province, $city);
                $tripDestination->setDescription($description);

                // Change the key as this is not in DB yet
                $tripDestination->setKey($tripDestination->getDestinationAsString());
                // Unset the old key association in $tripDestinations
                unset($tripDestinations[$destKey]);
                // Add new association to this destination in tripdestinations
                $tripDestinations[$tripDestination->getKey()] = $tripDestination;
            } else if ("delete" == $action) {
                // There can't be any X that depends on this destination since we don't allow "back" in wiz.
                // Simply unset the association in trip destinations. Note. $tripDestinations refer to the
                // trip destinations array in $trip object.
                unset($tripDestinations[$destKey]);
            }
        }
        $_SESSION['pageId'] = 'page' . $_POST['pageId'];
    } else if ("add" == $action || "addAndNext" == $action) {
        // OK, going to add a new destination.
        $tripDestination = new TripDestination($_POST['description']);
        $country = $_POST['country'];
        $city = $_POST['city'];
        $description = $_POST['description'];

        $state = NULL;
        $province = NULL;
        if ($_POST['stateProvinceOp'] == "province") {
            $province = $_POST['stateProvince'];
            $state = 'n/a';
        } else if ($_POST['stateProvinceOp'] == "state") {
            $state = $_POST['stateProvince'];
            $province = 'n/a';
        }
        $tripDestination->setDestination($country, $state, $province, $city);
        $tripDestination->setKey($tripDestination->getDestinationAsString());
        $tripDestinations = &$trip->getTripDestinations();
        // inDB and deleted should be FALSE by default. So just add a new association in trip destinations
        $tripDestinations[$tripDestination->getKey()] = $tripDestination;
        if ("addAndNext" == $action) {
            $_SESSION['pageId'] = 'page' . ($_POST['pageId'] + 1);
        } else {
            $_SESSION['pageId'] = 'page' . $_POST['pageId'];
        }
    } else if ("nextOnly" == $action) {
        $_SESSION['pageId'] = 'page' . ($_POST['pageId'] + 1);
    }
    header("Location:trip_design.php?status=pending");
}

function updateX($Xs, $country, $state, $province, $city, $tripDestination) {
    foreach ($Xs as $X) {
        if ($X->getCountry() == $tripDestination->getCountry() &&
                $X->getState() == $tripDestination->getState() &&
                $X->getProvince() == $tripDestination->getProvince() &&
                $X->getCity() == $tripDestination->getCity()) {
            // Aha! a matching X for this destination
            $X->setAction("update");
            $X->setCountry($country);
            $X->setState($state);
            $X->setProvince($province);
            $X->setCity($city);
        }
    }
}

function deleteX($Xs, $tripDestination) {
    foreach ($Xs as $X) {
        if ($X->getCountry() == $tripDestination->getCountry() &&
                $X->getState() == $tripDestination->getState() &&
                $X->getProvince() == $tripDestination->getProvince() &&
                $X->getCity() == $tripDestination->getCity()) {
            // Aha! a matching X for this destination
            $X->setAction("delete");
            $X->setDeleted(TRUE);
        }
    }
}

function handlePage0($trip) {
    // Basic trip info is posted. Put them into the trip and set pageId to 1 and go back to design
    $trip->setTitle($_POST['title']);
    $trip->setDescription($_POST['description']);
    $trip->setCost($_POST['cost']);
    $_SESSION['pageId'] = 'page1';
    header("Location:trip_design.php?status=pending");
}

function handleCancel() {
    // Cancel and go home
    unset($_SESSION['trip']);
    unset($_SESSION['pageId']);
    header("Location:index.php");
    exit();
}

?>
 
