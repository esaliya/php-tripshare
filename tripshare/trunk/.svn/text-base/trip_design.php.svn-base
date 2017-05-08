<?php
require('db_model.php');
session_start();
require('db_access.php');
require('links.php');

if (!isset($_SESSION['authUser'])) {
    // Not authroized. So go back to home page.
    header("Location:index.php");
}

dbConnect();
$authUser = $_SESSION['authUser'];
if (!isset($_REQUEST['status'])) {
    if (!isset($_REQUEST['tid'])) {
        // Great it's going to be a new trip
        // Create a new Trip and put it to session
        // Remember to set uid of the Trip to the authUser
        $_SESSION['trip'] = new Trip($authUser,NULL,NULL,0.0);
        $_SESSION['trip']->setAction("add");
    } else {
        $tid = $_REQUEST['tid'];
        $trip = Trip::fromDB($tid);
        if (!$trip || $trip->getUid() != $authUser) {
            // Trying to cheat eh?
            header("Location:index.php");
            exit();
        }
        // Good there was a trip for this tid in DB and user is authorized to edit it.
        // So let's put it in session.
        $_SESSION['trip'] = $trip;
        $_SESSION['trip']->setAction("modify");
    }
} else {
    $status = $_REQUEST['status'];
    if ("pending" == $status) {
        // half baked/re-baked trip should be in session. Otherwise bad request
        if (!isset($_SESSION['trip'])) {
            header("Location:index.php");
            exit();
        }
    } else {
        // Bad request.
        header("Location:index.php");
        exit();
    }
}

// There should be a trip in session by now.
$trip = $_SESSION['trip'];
$pageId = isset($_SESSION['pageId']) ? $_SESSION['pageId'] : 'page0';

// todo: skip other pages and go to photos directly
//if ($pageId == 'page4'){
//    $pageId = 'page7';
//
//}

$sep = "::";
$bigsep =";;";

// TripInfo string as title::description::cost
$tripInfoString = $trip->getTitle() . $sep . $trip->getDescription() . $sep . $trip->getCost();

$tripDestinationsJSON = $trip->tripDestinationsToJSON();
$restaurantsJSON = $trip->restaurantsToJSON();
$hotelsJSON= $trip->hotelsToJSON();
$attractionsJSON= $trip->attractionsToJSON();
$transportsJSON= $trip->transportsToJSON();
$avoidsJSON= $trip->avoidsToJSON();



?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
    <link rel="stylesheet" href="styles/style.css" type="text/css"/>
    <title>TripShare</title>
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>

    <script type="text/javascript">
        $(document).ready(init);
        function init() {
            // Show correct page at init
            showHideOnLoad();

            // Load page data
            loadPage0();
            loadPage1();
            loadPage2();
            loadPage3();
        }

        function showHideOnLoad() {
            var wizDiv = document.getElementById('wizDiv');
            for (var i = 0; i < wizDiv.childNodes.length; i++) {
                if (wizDiv.childNodes[i].nodeType == 1 &&
                        wizDiv.childNodes[i].getAttribute("id") != '<?php echo($pageId)?>') {
                    wizDiv.childNodes[i].style.display = "none";
                }
            }
        }

        // Functions on page0--------------------------------------------------------------------------
        function loadPage0() {
            var tripInfoString = '<?php echo($tripInfoString);?>';
            if (tripInfoString != '') {
                var params;
                params = tripInfoString.split("::");
                if (params[0] !=null && params[0] !='') {
                    document.getElementById('titleBx').value = params[0];
                }
                if (params[1] !=null && params[1] !='') {
                    document.getElementById('descriptionArea').value = params[1];
                }
                if (params[2] !=null && params[2] !='') {
                    document.getElementById('costBx').value = params[2];
                }
            }
        }
        function validateAndSubmit0 (){
            var error = false;
            var element = document.getElementById('titleBx');
            if (element.value == "") {
                makeError('titleBx', "Title cannot be empty!");
                error = true;
            }
            element = document.getElementById('costBx');

            if (element.value != '' && !isNumeric(element.value)) {
                makeError('costBx', "Numeric only!");
                error = true;
            }
            if (!error) {
                document.form0.action="trip_design_helper.php";
                document.form0.submit();
            }
        }
        //-----------------------------------------------------------------------------------------------------------

        // Functions and constants on page1--------------------------------------------------------------------------
        var tripDestinations = JSON.parse('<?php print($tripDestinationsJSON); ?>');
        function loadPage1() {
            var destSelect = document.getElementById('destSelect');
            loadDestinations(destSelect);
        }
        function page1Edit() {
            var destSelect = document.getElementById('destSelect');
            var selectedIdx = destSelect.selectedIndex;
            if (selectedIdx>=0) {
                var tripDestination = tripDestinations[selectedIdx];
                document.getElementById('page1DestKey').value = tripDestination.key;
                if (tripDestination.inDB) {
                    document.getElementById('page1InDB').value="true";
                } else {
                    document.getElementById('page1InDB').value="false";
                }
                hideElem('page1DefBtns');
                showElem('page1EditBtns');

                document.getElementById('page1CountryBx').value = tripDestination.country;
                if (tripDestination.state =='n/a') {
                    document.getElementById('stateRadio').removeAttribute("checked");
                } else {
                    document.getElementById('provinceRadio').removeAttribute("checked");
                    document.getElementById('stateRadio').removeAttribute("checked");
                    document.getElementById('stateRadio').setAttribute("checked","checked");
                    document.getElementById('page1StateProvinceBx').value = tripDestination.state;

                }

                if (tripDestination.province =='n/a') {
                    document.getElementById('provinceRadio').removeAttribute("checked");
                } else {
                    document.getElementById('stateRadio').removeAttribute("checked");
                    document.getElementById('provinceRadio').removeAttribute("checked");
                    document.getElementById('provinceRadio').setAttribute("checked","checked");
                    document.getElementById('page1StateProvinceBx').value = tripDestination.province;
                }
                document.getElementById('page1CityBx').value = tripDestination.city;
                document.getElementById('page1DescriptionArea').value = tripDestination.description;

            }
        }
        function page1Update(){
            if (page1Validate()) {
                document.getElementById('page1Action').value = "update";
                document.form11.action="trip_design_helper.php";
                document.form11.submit();
            }
        }
        function page1Delete(){
            if (confirm("This will delete any information associated with the particular destination.\n\nDo you want to continue?")) {
                document.getElementById('page1Action').value = "delete";
                document.form11.action="trip_design_helper.php";
                document.form11.submit();
            }
        }
        function page1AddMore() {
            if (page1Validate()) {
                document.getElementById('page1Action').value = "add";
                document.form11.action="trip_design_helper.php";
                document.form11.submit();
            }
        }
        function page1Next (){
            var error = false;
            if (document.getElementById('destSelect').options.length>0) {
                // There is at least one destination for this trip (even though not required by our DB design)
                if (jQuery.trim(document.getElementById('page1CountryBx').value) == "" &&
                        jQuery.trim(document.getElementById('page1StateProvinceBx').value) == "" &&
                        jQuery.trim(document.getElementById('page1CityBx').value) =="" &&
                        jQuery.trim(document.getElementById('page1DescriptionArea').value) =="") {
                    // No data entered.
                    document.getElementById('page1Action').value = "nextOnly";
                } else if (page1Validate()) {
                    // Some data is yet to be preserved
                    document.getElementById('page1Action').value = "addAndNext";
                } else {
                    error = true;
                }
            } else if (page1Validate()) {
                document.getElementById('page1Action').value = "addAndNext";
            } else {error = true;}

            if (!error) {
                document.form11.action="trip_design_helper.php";
                document.form11.submit();
            }
        }
        function page1Validate() {
            var error = false;
            var element = document.getElementById('page1CountryBx');
            if (jQuery.trim(element.value) == "" || element.style.color == "red") {
                makeError('page1CountryBx', "Title cannot be empty!");
                error = true;
            }
            element = document.getElementById('page1StateProvinceBx');

            if (jQuery.trim(element.value) == '' || element.style.color == "red") {
                makeError('page1StateProvinceBx', "State/Province cannot be empty!");
                error = true;
            }

            element = document.getElementById('page1CityBx');

            if (jQuery.trim(element.value) == '' || element.style.color == "red") {
                makeError('page1CityBx', "City cannot be empty!");
                error = true;
            }
            return !error;
        }
        function page1CancelEdit(){
            document.getElementById('page1CountryBx').value='';
            document.getElementById('page1StateProvinceBx').value='';
            document.getElementById('page1CityBx').value='';
            document.getElementById('page1DescriptionArea').value='';
            document.getElementById('provinceRadio').removeAttribute("checked");
            document.getElementById('stateRadio').setAttribute("checked","checked");
            hideElem('page1EditBtns');
            showElem('page1DefBtns');
        }
        //-----------------------------------------------------------------------------------------------------------

        // Functions and constants on page3--------------------------------------------------------------------------
        var hotels = JSON.parse('<?php print($hotelsJSON); ?>');
        function loadPage3() {
            var destSelect = document.getElementById('page3DestSelect');
            loadDestinations(destSelect);

            var restSelect = document.getElementById('page3HotelSelect');
            for (var i = 0; i< hotels.length; i++) {
                if (!hotels[i].deleted) {
                    restSelect.options[restSelect.length] = new Option(hotels[i].name, hotels[i].name);
                } else {
                    hotels.splice(i,1);
                    i--;
                }
            }
        }
        function page3Edit() {
            var hotelSelect = document.getElementById('page3HotelSelect');
            var selectedIdx = hotelSelect.selectedIndex;
            if (selectedIdx>=0) {
                var hotel = hotels[selectedIdx];
                // Select correct destination in page3DestSelect
                var delCount = 0;
                for (var i=0; i < tripDestinations.length; i++) {
                    if (!tripDestinations[i].deleted) {
                        if (tripDestinations[i].key == hotel.key) {
                            document.getElementById('page3DestSelect').selectedIndex = i - delCount;
                            break;
                        }
                    } else {
                        delCount++;
                    }
                }

                document.getElementById('page3NameBx').value = hotel.name;

                // Select correct stars in page3StarSelect
                var startSelect = document.getElementById('page3StarSelect');
                for(i=0; i < startSelect.options.length; i++) {
                    if (startSelect.options[i].value == hotel.stars) {
                        startSelect.selectedIndex = i;
                        break;
                    }
                }

                document.getElementById('page3Comments').value = hotel.comments;
                document.getElementById('page3AvgCost').value = hotel.avgCost;
                document.getElementById('page3StreetAddress').value = hotel.streetAddress;
                document.getElementById('page3Telephone').value = hotel.telephone;
                document.getElementById('page3Email').value = hotel.email;
                document.getElementById('page3URL').value = hotel.url;

                hideElem('page3DefBtns');
                showElem('page3EditBtns');
                if (hotel.inDB) {
                    document.getElementById('page3InDB').value="true";
                } else {
                    document.getElementById('page3InDB').value="false";
                }
                document.getElementById('page3Hid').value = hotel.hid;
            }
        }
        function page3Update(){
            if (page3Validate()) {
                setDestKey('page3DestSelect', 'page3DestKey');
                document.getElementById('page3Action').value = "update";
                document.form31.action="trip_design_helper.php";
                document.form31.submit();
            }
        }
        function page3Delete(){
            // no need to do anything for the values of elements as they will not be considered
            var hotelName = document.getElementById('page3NameBx');
            if (confirm("Are you sure you want to delete " + hotelName + "?")) {
                document.getElementById('page3Action').value = "delete";
                document.form31.action="trip_design_helper.php";
                document.form31.submit();
            }
        }
        function page3AddMore() {
            if (page3Validate()) {
                setDestKey('page3DestSelect', 'page3DestKey');
                document.getElementById('page3Action').value = "add";
                document.form31.action="trip_design_helper.php";
                document.form31.submit();
            }
        }
        function page3Next (){
            var error = false;
            // todo: check empty for other elements as well
            if (jQuery.trim(document.getElementById('page3NameBx').value) == ""){
                // No data entered.
                document.getElementById('page3Action').value = "nextOnly";
            } else if (page3Validate()) {
                setDestKey('page3DestSelect', 'page3DestKey');
                // Some data is yet to be preserved
                document.getElementById('page3Action').value = "addAndNext";
            } else {
                error = true;
            }

            if (!error) {
                document.form31.action="trip_design_helper.php";
                document.form31.submit();
            }
        }
        function page3Validate() {
            var error = false;
            var element = document.getElementById('page3NameBx');
            if (jQuery.trim(element.value) == "" || element.style.color == "red") {
                makeError('page3NameBx', "Name cannot be empty!");
                error = true;
            }
            return !error;
        }
        function page3CancelEdit(){
            document.getElementById('page3DestSelect').selectedIndex = 0;
            document.getElementById('page3NameBx').value='';
            document.getElementById('page3StarSelect').selectedIndex=0;
            document.getElementById('page3Comments').value='';
            document.getElementById('page3AvgCost').value='';
            document.getElementById('page3StreetAddress').value='';
            document.getElementById('page3Telephone').value='';
            document.getElementById('page3Email').value='';
            document.getElementById('page3URL').value='';
            hideElem('page3EditBtns');
            showElem('page3DefBtns');
        }
        //-----------------------------------------------------------------------------------------------------------


        // Functions and constants on page2--------------------------------------------------------------------------
        var restaurants = JSON.parse('<?php print($restaurantsJSON); ?>');
        function loadPage2() {
            var destSelect = document.getElementById('page2DestSelect');
            loadDestinations(destSelect);

            var restSelect = document.getElementById('page2RestSelect');
            for (var i = 0; i< restaurants.length; i++) {
                if (!restaurants[i].deleted) {
                    restSelect.options[restSelect.length] = new Option(restaurants[i].name, restaurants[i].name);
                } else {
                    tripDestinations.splice(i,1);
                    i--;
                }
            }
        }
        function page2Edit() {
            var restSelect = document.getElementById('page2RestSelect');
            var selectedIdx = restSelect.selectedIndex;
            if (selectedIdx>=0) {
                var restaurant = restaurants[selectedIdx];
                // Select correct destination in page2DestSelect
                var delCount = 0;
                for (var i=0; i < tripDestinations.length; i++) {
                    if (!tripDestinations[i].deleted) {
                        if (tripDestinations[i].key == restaurant.key) {
                            document.getElementById('page2DestSelect').selectedIndex = i - delCount;
                            break;
                        }
                    } else {
                        delCount++;
                    }
                }

                document.getElementById('page2NameBx').value = restaurant.name;

                // Select correct type in page2TypeSelect
                var typeSelect = document.getElementById('page2TypeSelect');
                for(i=0; i < typeSelect.options.length; i++) {
                    if (typeSelect.options[i].value == restaurant.type) {
                        typeSelect.selectedIndex = i;
                        break;
                    }
                }

                document.getElementById('page2Comments').value = restaurant.comments;
                document.getElementById('page2AvgCost').value = restaurant.avgCost;
                document.getElementById('page2StreetAddress').value = restaurant.streetAddress;
                document.getElementById('page2Telephone').value = restaurant.telephone;
                document.getElementById('page2Email').value = restaurant.email;
                document.getElementById('page2URL').value = restaurant.url;

                hideElem('page2DefBtns');
                showElem('page2EditBtns');
                if (restaurant.inDB) {
                    document.getElementById('page2InDB').value="true";
                } else {
                    document.getElementById('page2InDB').value="false";
                }
                document.getElementById('page2Rid').value = restaurant.rid;
            }
        }
        // todo from here
        function page2Update(){
            if (page2Validate()) {
                setDestKey('page2DestSelect', 'page2DestKey');
                document.getElementById('page2Action').value = "update";
                document.form21.action="trip_design_helper.php";
                document.form21.submit();
            }
        }
        function page2Delete(){
            // no need to do anything for the values of elements as they will not be considered
            var restName = document.getElementById('page2NameBx');
            if (confirm("Are you sure you want to delete " + restName + "?")) {
                document.getElementById('page2Action').value = "delete";
                document.form21.action="trip_design_helper.php";
                document.form21.submit();
            }
        }
        function page2AddMore() {
            if (page2Validate()) {
                setDestKey('page2DestSelect', 'page2DestKey');
                document.getElementById('page2Action').value = "add";
                document.form21.action="trip_design_helper.php";
                document.form21.submit();
            }
        }
        function page2Next (){
            var error = false;
            // todo: check empty for other elements as well
            if (jQuery.trim(document.getElementById('page2NameBx').value) == ""){
                // No data entered.
                document.getElementById('page2Action').value = "nextOnly";
            } else if (page2Validate()) {
                setDestKey('page2DestSelect', 'page2DestKey');
                // Some data is yet to be preserved
                document.getElementById('page2Action').value = "addAndNext";
            } else {
                error = true;
            }

            if (!error) {
                document.form21.action="trip_design_helper.php";
                document.form21.submit();
            }
        }
        function page2Validate() {
            var error = false;
            var element = document.getElementById('page2NameBx');
            if (jQuery.trim(element.value) == "" || element.style.color == "red") {
                makeError('page2NameBx', "Name cannot be empty!");
                error = true;
            }
            return !error;
        }
        function page2CancelEdit(){
            document.getElementById('page2DestSelect').selectedIndex = 0;
            document.getElementById('page2NameBx').value='';
            document.getElementById('page2TypeSelect').selectedIndex=0;
            document.getElementById('page2Comments').value='';
            document.getElementById('page2AvgCost').value='';
            document.getElementById('page2StreetAddress').value='';
            document.getElementById('page2Telephone').value='';
            document.getElementById('page2Email').value='';
            document.getElementById('page2URL').value='';
            hideElem('page2EditBtns');
            showElem('page2DefBtns');
        }
        //-----------------------------------------------------------------------------------------------------------


        // General Functions
        function setDestKey(destSelectId, destKeyId) {
            // Select correct destination key in destKeyId
            var destSelectElem = document.getElementById(destSelectId);
            var selectedIdx = destSelectElem.selectedIndex;
            var destKeyElem = document.getElementById(destKeyId);
            for (var i=0; i < tripDestinations.length; i++) {
                // At this state tripDestinations will not have deleted destinations (see loadDestinations())
                if (i == selectedIdx) {
                    destKeyElem.value = tripDestinations[i].key;
                    break;
                }
            }
        }
        function loadDestinations(destSelectElem) {
            for (var i = 0; i< tripDestinations.length; i++) {
                if (!tripDestinations[i].deleted) {
                    var destinationString = tripDestinations[i].country + ", ";
                    if (tripDestinations[i].state !='n/a') {
                        destinationString = destinationString + tripDestinations[i].state;
                    } else if (tripDestinations[i].province !='n/a') {
                        destinationString = destinationString + tripDestinations[i].province;
                    }
                    destinationString = destinationString + ", " + tripDestinations[i].city;

                    destSelectElem.options[destSelectElem.length] = new Option(destinationString, destinationString);
                } else {
                    tripDestinations.splice(i,1);
                    i--;
                }
            }
        }
        function cancelTrip(formId) {
            if (confirm("Are you sure you want to discard current trip information?")) {
                var form = document.getElementById(formId);
                form.action= "trip_design_helper.php?cancel=true";
                form.submit();
            }
        }

        function makeError(id, msg) {
            var element = document.getElementById(id);
            element.style.color = "red";
            element.style.fontSize="inherit";
            element.style.padding="3px 0";
            element.value = msg;
        }

        function makeNormal(id) {
            var element = document.getElementById(id);
            if (element.style.color == "red") {
                element.value='';
                element.style.color="#454545";
                element.style.font="Tahoma,sans-serif";
            }
        }

        function isNumeric(strString) {
            var strValidChars = "0123456789.-";
            var strChar;
            var blnResult = true;

            if (strString.length == 0) return false;

            //  test strString consists of valid characters listed above
            for (i = 0; i < strString.length && blnResult; i++) {
                strChar = strString.charAt(i);
                if (strValidChars.indexOf(strChar) == -1) {
                    blnResult = false;
                }
            }
            return blnResult;
        }

        function hideElem(objid) {
            var theObj = document.getElementById(objid);
            if (theObj) {
                theObj.style.display = "none";
            }
        }
        function showElem(objid) {
            var theObj = document.getElementById(objid);
            if (theObj) {
                theObj.style.display = "";
            }
        }


    </script>

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
        <?php echo genNavi("New Trip"); ?>
    </div>

    <div>
        <div class="gap"></div>
        <div id="wizDiv"">
        <div id="page0">
            <div class="left-small"><h3>Trip Info</h3></div>
            <div class="box">
                <form id="formId0" name="form0" action="" method="post">
                    <table>
                        <tr>
                            <td>Title: <span style="color:red">*</span></td>
                            <td>
                                <label>
                                    <input id="titleBx" type="text" name="title"
                                           style="width:300px; font-size:inherit; padding:3px 0"
                                           onclick="makeNormal('titleBx');"/>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>Description:</td>
                            <td>
                                <label>
                                    <textarea id="descriptionArea" rows="5" cols="30" name="description"
                                              style="width:300px"></textarea>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Cost ($):</td>
                            <td><label><input type="text" name="cost" id="costBx"
                                              style="width:300px; font-size:inherit; padding:3px 0"
                                              onclick="makeNormal('costBx');"></label></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right">
                                <label><input type="button" name="cancel" value="Cancel"
                                              class="submit" onclick="cancelTrip('formId0');"/></label>
                                <label><input type="submit" name="next" value="Next" class="submit"
                                              onclick="validateAndSubmit0();return false;"/></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><input type="hidden" name="pageId" value="0"/></label>
<!--                                <label><input type="hidden" id="wizActionField0" name="wizAction" value=""/></label>-->
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <div id="page1">
            <div class="left-small"><h3>Destinations</h3></div>
            <div class="box">
                <table>
                    <tr>
                        <td>
                            <form id="formId11" name="form11" action="" method="post">
                                <table>
                                    <tr>
                                        <td>Country: <span style="color:red">*</span></td>
                                        <td>
                                            <label>
                                                <input id="page1CountryBx" type="text" name="country"
                                                       style="width:300px; font-size:inherit; padding:3px 0"
                                                       onclick="makeNormal('page1CountryBx');"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>State/Province: <span style="color:red">*</span></td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td><label>
                                                        <input id="stateRadio" name="stateProvinceOp" value="state"
                                                               type="radio" checked>State
                                                    </label></td>
                                                    <td><label>
                                                        <input id="provinceRadio" name="stateProvinceOp"
                                                               value="province" type="radio">Province
                                                    </label></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <label>
                                                <input id="page1StateProvinceBx" type="text" name="stateProvince"
                                                       style="width:300px; font-size:inherit; padding:3px 0"
                                                       onclick="makeNormal('page1StateProvinceBx');"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>City: <span style="color:red">*</span></td>
                                        <td><label><input type="text" name="city" id="page1CityBx"
                                                          style="width:300px; font-size:inherit; padding:3px 0"
                                                          onclick="makeNormal('page1CityBx');"></label></td>
                                    </tr>
                                    <tr>
                                        <td>Description:</td>
                                        <td>
                                            <label>
                                                <textarea id="page1DescriptionArea" rows="5" cols="30" name="description"
                                                          style="width:300px;"></textarea>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right" id="page1DefBtns">
                                            <label><input type="button" name="cancel" value="Cancel"
                                                          class="submit"
                                                          onclick="cancelTrip('formId11');"/></label>
                                            <label><input type="button" name="addMore" id="addMoreBtn"
                                                          value="Add More" class="submit"
                                                          onclick="page1AddMore();"/></label>
                                            <label><input type="submit" name="next" value="Next" class="submit"
                                                          onclick="page1Next();return false;"/></label>
                                        </td>
                                        <td colspan="2" align="right" id="page1EditBtns" style="display:none;">
                                            <label><input type="button" name="cancel" value="Cancel"
                                                          class="submit"
                                                          onclick="page1CancelEdit();"/></label>
                                            <label><input type="button" name="update" id="updateBtn"
                                                          value="Update" class="submit"
                                                          onclick="page1Update();"/></label>
                                            <label>
                                                <input id="page1DelBtn" type="button" name="delete"
                                                       value="Delete" class="submit"
                                                       onclick="page1Delete();"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label><input type="hidden" name="pageId" value="1"/></label>
                                            <label><input type="hidden" name="page1Action" value="add" id="page1Action"/></label>
                                            <label><input type="hidden" name="page1InDb" value="" id="page1InDB"/></label>
                                            <label><input type="hidden" name="page1DestKey" value="" id="page1DestKey"/></label>
                                        </td>
                                    </tr>
                                </table>
                            </form>

                        </td>
                        <td><div style="width:25px"></div></td>
                        <td><div style="width:0px;height:250px;border: 1px solid #E3A554;"></div></td>
                        <td><div style="width:25px"></div></td>
                        <td>
                            <form action="trip_design_helper.php" method="post" name="form12" id="formId12">
                                <table>
                                    <tr>
                                        <td>Destinations in Your Trip:</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <select id="destSelect" name="destSelect" SIZE="10" style="width:250px;font-size:inherit; padding:3px 0;height:195px">
                                                    
                                                </select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right">
                                            <label>
                                                <input id="page1EditBtn" type="button" name="Edit"
                                                       value="Edit" class="submit"
                                                       onclick="page1Edit();"/>
                                            </label>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label><input type="hidden" name="pageId" value="1"/></label>
                                            <label><input type="hidden" id="wizActionField1-edit" name="wizAction" value=""/></label>
                                        </td>
                                    </tr>

                                </table>
                            </form>

                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="page2">
            <div class="left-small"><h3>Restaurants</h3></div>
            <div class="box">
                <table>
                    <tr>
                        <td>
                            <form id="formId21" name="form21" action="" method="post">
                                <table>
                                    <tr>
                                        <td>Destination: </td>
                                        <td>
                                            <label>
                                                <select id="page2DestSelect" name="destSelect"
                                                        style="font-size:inherit; padding:3px 0;width:305px;">/select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Name: <span style="color:red">*</span></td>
                                        <td>
                                            <label>
                                                <input id="page2NameBx" type="text" name="name"
                                                       style="width:300px; font-size:inherit; padding:3px 0"
                                                       onclick="makeNormal('page2NameBx');"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Type: </td>
                                        <td>
                                            <label>
                                                <select id="page2TypeSelect" name="typeSelect" style="font-size:inherit; padding:3px 0;">
                                                    <option value="American" selected="selected">American</option>
                                                    <option value="Asian">Asian</option>
                                                    <option value="Chinese">Chinese</option>
                                                    <option value="Mexican">Mexican</option>
                                                    <option value="Sri Lankan">Sri Lankan</option>
                                                    <option value="Mix">Mix</option>
                                                    <option value="Vegetarian">Vegetarian</option>
                                                </select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Comments: </td>
                                        <td>
                                            <label>
                                                <textarea id="page2Comments" rows="5" cols="30" name="comments"
                                                          style="width:300px;"></textarea>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Avg. Cost ($): </td>
                                        <td>
                                            <label>
                                                <input id="page2AvgCost" type="text" name="avgCost"
                                                       style="width:300px; font-size:inherit; padding:3px 0"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Street Adderss: </td>
                                        <td>
                                            <label>
                                                <input id="page2StreetAddress" type="text" name="streetAddress"
                                                       style="width:300px; font-size:inherit; padding:3px 0"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Phone: </td>
                                        <td>
                                            <label>
                                                <input id="page2Telephone" type="text" name="telephone"
                                                       style="width:300px; font-size:inherit; padding:3px 0"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email: </td>
                                        <td>
                                            <label>
                                                <input id="page2Email" type="text" name="email"
                                                       style="width:300px; font-size:inherit; padding:3px 0"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>URL: </td>
                                        <td>
                                            <label>
                                                <input id="page2URL" type="text" name="url"
                                                       style="width:300px; font-size:inherit; padding:3px 0"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right" id="page2DefBtns">
                                            <label><input type="button" name="cancel" value="Cancel"
                                                          class="submit"
                                                          onclick="cancelTrip('formId21');"/></label>
                                            <label><input type="button" name="addMore" id="page2AddMoreBtn"
                                                          value="Add More" class="submit"
                                                          onclick="page2AddMore();"/></label>
                                            <label><input type="submit" name="next" value="Next" class="submit"
                                                          onclick="page2Next();return false;"/></label>
                                        </td>
                                        <td colspan="2" align="right" id="page2EditBtns" style="display:none;">
                                            <label><input type="button" name="cancel" value="Cancel"
                                                          class="submit"
                                                          onclick="page2CancelEdit();"/></label>
                                            <label><input type="button" name="update" id="page2UpdateBtn"
                                                          value="Update" class="submit"
                                                          onclick="page2Update();"/></label>
                                            <label>
                                                <input id="page2DelBtn" type="button" name="delete"
                                                       value="Delete" class="submit"
                                                       onclick="page2Delete();"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label><input type="hidden" name="pageId" value="2"/></label>
                                            <label><input type="hidden" name="page2Action" value="add" id="page2Action"/></label>
                                            <label><input type="hidden" name="page2InDb" value="" id="page2InDB"/></label>
                                            <label><input type="hidden" name="page2DestKey" value="" id="page2DestKey"/></label>
                                            <label><input type="hidden" name="page2Rid" value="" id="page2Rid"/></label>
                                        </td>
                                    </tr>
                                </table>
                            </form>

                        </td>
                        <td><div style="width:25px"></div></td>
                        <td><div style="width:0px;height:370px;border: 1px solid #E3A554;"></div></td>
                        <td><div style="width:25px"></div></td>
                        <td>
                            <form action="trip_design_helper.php" method="post" name="form22" id="formId22">
                                <table>
                                    <tr>
                                        <td>Restaurants in Your Trip:</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <select id="page2RestSelect" name="restSelect" size="20"
                                                        style="width:260px;font-size:inherit; padding:3px 0;height:315px">

                                                </select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right">
                                            <label>
                                                <input id="page2EditBtn" type="button" name="Edit"
                                                       value="Edit" class="submit"
                                                       onclick="page2Edit();"/>
                                            </label>

                                        </td>
                                    </tr>
                                </table>
                            </form>

                        </td>
                    </tr>
                </table>
            </div>

        </div>
        <div id="page3">
            <div class="left-small"><h3>Hotels</h3></div>
            <div class="box">
                <table>
                    <tr>
                        <td>
                            <form id="formId31" name="form31" action="" method="post">
                                <table>
                                    <tr>
                                        <td>Destination: </td>
                                        <td>
                                            <label>
                                                <select id="page3DestSelect" name="destSelect"
                                                        style="font-size:inherit; padding:3px 0;width:305px;">/select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Name: <span style="color:red">*</span></td>
                                        <td>
                                            <label>
                                                <input id="page3NameBx" type="text" name="name"
                                                       style="width:300px; font-size:inherit; padding:3px 0"
                                                       onclick="makeNormal('page3NameBx');"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Stars: </td>
                                        <td>
                                            <label>
                                                <select id="page3StarSelect" name="starSelect" style="font-size:inherit; padding:3px 0;">
                                                    <option value="7" selected="selected">Seven Star</option>
                                                    <option value="6">Six Star</option>
                                                    <option value="5">Five Star</option>
                                                    <option value="4">Four Star</option>
                                                    <option value="3">Three Star</option>
                                                    <option value="2">Two Star</option>
                                                    <option value="0">NoStar</option>
                                                </select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Comments: </td>
                                        <td>
                                            <label>
                                                <textarea id="page3Comments" rows="5" cols="30" name="comments"
                                                          style="width:300px;"></textarea>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Avg. Cost ($): </td>
                                        <td>
                                            <label>
                                                <input id="page3AvgCost" type="text" name="avgCost"
                                                       style="width:300px; font-size:inherit; padding:3px 0"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Street Adderss: </td>
                                        <td>
                                            <label>
                                                <input id="page3StreetAddress" type="text" name="streetAddress"
                                                       style="width:300px; font-size:inherit; padding:3px 0"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Phone: </td>
                                        <td>
                                            <label>
                                                <input id="page3Telephone" type="text" name="telephone"
                                                       style="width:300px; font-size:inherit; padding:3px 0"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email: </td>
                                        <td>
                                            <label>
                                                <input id="page3Email" type="text" name="email"
                                                       style="width:300px; font-size:inherit; padding:3px 0"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>URL: </td>
                                        <td>
                                            <label>
                                                <input id="page3URL" type="text" name="url"
                                                       style="width:300px; font-size:inherit; padding:3px 0"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right" id="page3DefBtns">
                                            <label><input type="button" name="cancel" value="Cancel"
                                                          class="submit"
                                                          onclick="cancelTrip('formId31');"/></label>
                                            <label><input type="button" name="addMore" id="page3AddMoreBtn"
                                                          value="Add More" class="submit"
                                                          onclick="page3AddMore();"/></label>
                                            <label><input type="submit" name="next" value="Next" class="submit"
                                                          onclick="page3Next();return false;"/></label>
                                        </td>
                                        <td colspan="2" align="right" id="page3EditBtns" style="display:none;">
                                            <label><input type="button" name="cancel" value="Cancel"
                                                          class="submit"
                                                          onclick="page3CancelEdit();"/></label>
                                            <label><input type="button" name="update" id="page3UpdateBtn"
                                                          value="Update" class="submit"
                                                          onclick="page3Update();"/></label>
                                            <label>
                                                <input id="page3DelBtn" type="button" name="delete"
                                                       value="Delete" class="submit"
                                                       onclick="page3Delete();"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label><input type="hidden" name="pageId" value="3"/></label>
                                            <label><input type="hidden" name="page3Action" value="add" id="page3Action"/></label>
                                            <label><input type="hidden" name="page3InDb" value="" id="page3InDB"/></label>
                                            <label><input type="hidden" name="page3DestKey" value="" id="page3DestKey"/></label>
                                            <label><input type="hidden" name="page3Hid" value="" id="page3Hid"/></label>
                                        </td>
                                    </tr>
                                </table>
                            </form>

                        </td>
                        <td><div style="width:25px"></div></td>
                        <td><div style="width:0px;height:370px;border: 1px solid #E3A554;"></div></td>
                        <td><div style="width:25px"></div></td>
                        <td>
                            <form action="trip_design_helper.php" method="post" name="form32" id="formId32">
                                <table>
                                    <tr>
                                        <td>Hotels in Your Trip:</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <select id="page3HotelSelect" name="hotelSelect" size="20"
                                                        style="width:260px;font-size:inherit; padding:3px 0;height:315px">

                                                </select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right">
                                            <label>
                                                <input id="page3EditBtn" type="button" name="Edit"
                                                       value="Edit" class="submit"
                                                       onclick="page3Edit();"/>
                                            </label>

                                        </td>
                                    </tr>
                                </table>
                            </form>

                        </td>
                    </tr>
                </table>
            </div>


        </div>
        <div id="page4">
<!--            <h3>Attractions</h3>-->
            <div class="left-small"><h3>Submit Trip</h3></div>
            <div class="box">
                <table>
                    <tr>
                        <td>
                            <form action="trip_design_helper.php" method="post">
                                <label>
                                    <input type="submit" name="testsubmit" value="Done" class="submit">
                                </label>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
        <div id="page5">
            <h3>Transports</h3>

        </div>
        <div id="page6">
            <h3>Avoids</h3>

        </div>
        <div id="page7">
            <h3>Photos</h3>
        </div>
    </div>

    <div class="footer">
        <p><a href="contact.php">Contact</a><br/>&copy; Copyright 2010 TripShare</p>

    </div>
</div>


</body>
</html>

