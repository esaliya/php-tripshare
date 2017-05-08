<?php
/**
 * Created by PhpStorm.
 * User: saliya
 */

class Trip {
    const TABLE = "trips";

    private $_tid = NULL;
    private $_uid = NULL;
    private $_title = NULL;
    private $_description = "";
    private $_cost = 0.0;
    private $_date = NULL;

    private $_restaurants = array();
    private $_hotels = array();
    private $_attractions = array();
    private $_transports = array();
    private $_avoids = array();
    private $_trip_destinations = array();

    private $_action = "add";

    // Note. tid is not present in the constructor as it's auto generated.
    // Also, date is not present in the constructor. It should be set using accessors.
    function __construct($uid, $title, $description, $cost) {
        $this->_uid = $uid;
        $this->_title = $title;
        $this->_description = $description;
        $this->_cost = $cost;
    }

    function toDB() {
        // Action add is set at the time of creation through population from DB by trip_design.php
        if ("add" == $this->_action) {
            date_default_timezone_set("America/Indianapolis");
            $date = date("Y-m-d");
            $query = "INSERT INTO " . self::TABLE . " VALUES (NULL,'$this->_uid','$this->_title','$this->_description','$this->_cost', '$date')";
            if (@mysql_query($query)) {
                $this->_tid = mysql_insert_id();

                // Set this tid on all Xs
                $this->setTidOnXorDests($this->_trip_destinations, $this->_tid);
                $this->setTidOnXorDests($this->_restaurants, $this->_tid);
                $this->setTidOnXorDests($this->_hotels, $this->_tid);
                $this->setTidOnXorDests($this->_attractions, $this->_tid);
                $this->setTidOnXorDests($this->_transports, $this->_tid);
                $this->setTidOnXorDests($this->_avoids, $this->_tid);

                // Then call toDB on destinations followed by Xs' toDB
                $this->callToDB($this->_trip_destinations);
                $this->callToDB($this->_restaurants);
                $this->callToDB($this->_hotels);
                $this->callToDB($this->_attractions);
                $this->callToDB($this->_transports);
                $this->callToDB($this->_avoids);
                return true;
            }
        } else if ("modify" == $this->_action) {
            // Update info for the tirp.
            $query = "UPDATE " . self::TABLE . " SET title='$this->_title', description='$this->_description', cost='$this->_cost' WHERE tid='$this->_tid'";
            if (@mysql_query($query)) {
                // Update went OK. Now update Xs and Destinations.
                $this->callToDB($this->_trip_destinations);
                $this->callToDB($this->_restaurants);
                $this->callToDB($this->_hotels);
                $this->callToDB($this->_attractions);
                $this->callToDB($this->_transports);
                $this->callToDB($this->_avoids);

                // Garbage collection run for trip_destinations;
                $this->callToDB($this->_trip_destinations);
            }
        }
        return false;
    }

    function callToDB($XorDests){
        foreach($XorDests as $o) {
            $o->toDB();
        }
    }

    function setTidOnXorDests($XorDests, $tid) {
        foreach ($XorDests as $X) {
            $X->setTid($tid);
        }
    }

    static function fromDB($tid) {
        $query = "SELECT * from " . self::TABLE . " WHERE tid='$tid';";
        $result = @mysql_query($query);
        if ($result) {
            $row = mysql_fetch_assoc($result);
            if (mysql_num_rows($result) == 1) {
                $trip = new Trip($row['uid'], $row['title'], $row['description'], $row['cost']);
                $trip->setTid($row['tid']);
                $trip->setRestaurants(Restaurant::fromDB($tid));
                $trip->setHotels(Hotel::fromDB($tid));
                $trip->setAttractions(Attraction::fromDB($tid));
                $trip->setTransports(Transport::fromDB($tid));
                $trip->setAvoids(Avoid::fromDB($tid));
                $trip->setTripDestinations(TripDestination::fromDB($tid));
                $trip->setAction("modify");
                return $trip;
            }
        }
        return false;
    }

    // Accessors
    function getTid() {return $this->_tid;}
    function getUid() {return $this->_uid;}
    function getTitle() {return $this->_title;}
    function getDescription() {return $this->_description;}
    function getCost() {return $this->_cost;}
    function getDate() {return $this->_date;}

    function &getRestaurants() {return $this->_restaurants;}
    function &getHotels() {return $this->_hotels;}
    function &getAttractions() {return $this->_attractions;}
    function &getTransports() {return $this->_transports;}
    function &getAvoids() {return $this->_avoids;}
    function &getTripDestinations() {return $this->_trip_destinations;}

    function getAction(){return $this->_action;}


    function setTid($value) {$this->_tid = $value;}
    function setUid($value) {$this->_uid = $value;}
    function setTitle($value) {$this->_title = $value;}
    function setDescription($value) {$this->_description = $value;}
    function setCost($value) {$this->_cost = $value;}
    function setDate($value) {$this->_date = $value;}

    function addRestaurant($value) {$this->_restaurants[$value->getRid()] = $value;}
    function addHotel($value) {$this->_hotels[$value->getHid()] = $value;}
    function addAttraction($value) {$this->_attractions[$value->getAid()] = $value;}
    function addTransport($value) {$this->_transports[$value->getTransid()] = $value;}
    function addAvoid($value) {$this->_avoids[$value->getAvid()] =  $value;}
    function addTripDestination($value) {$this->_trip_destinations[$value->getKey()] =  $value;}

    function setRestaurants($value) {$this->_restaurants = $value;}
    function setHotels($value) {$this->_hotels = $value;}
    function setAttractions($value) {$this->_attractions = $value;}
    function setTransports($value) {$this->_transports = $value;}
    function setAvoids($value) {$this->_avoids = $value;}
    function setTripDestinations($value) {$this->_trip_destinations = $value;}

    function setAction($value) {$this->_action = $value;}

    function tripDestinationsToJSON() {
        $json = array();
        foreach ($this->_trip_destinations as $tripDestination) {
            $arr = array();
            $arr["country"] = $tripDestination->getCountry();
            $arr["state"] = $tripDestination->getState();
            $arr["province"] = $tripDestination->getProvince();
            $arr["city"] = $tripDestination->getCity();
            $arr["description"] = $tripDestination->getDescription();
            $arr["key"] = $tripDestination->getKey();
            $arr["inDB"] = $tripDestination->getInDB();
            $arr["deleted"] = $tripDestination->getDeleted();
            $json[] = $arr;
        }
        return json_encode($json);
    }

    function restaurantsToJSON() {
        $json = array();
        foreach ($this->_restaurants as $restaurant) {
            $arr = array();
            $arr["key"] = $restaurant->getDestinationAsString();
            $arr["name"] = $restaurant->getName();
            $arr["type"] = $restaurant->getType();
            $arr["comments"] = $restaurant->getComments();
            $arr["avgCost"] = $restaurant->getAvgCost();
            $arr["streetAddress"] = $restaurant->getStreetAddress();
            $arr["telephone"] = $restaurant->getTelephone();
            $arr["email"] = $restaurant->getEmail();
            $arr["url"] = $restaurant->getUrl();
            $arr["rid"] = $restaurant->getRid();
            $arr["inDB"] = $restaurant->getInDB();
            $arr["deleted"] = $restaurant->getDeleted();
            $json[] = $arr;
        }
        return json_encode($json);
    }

    function hotelsToJSON() {
        $json = array();
        foreach ($this->_hotels as $hotel) {
            $arr = array();
            $arr["key"] = $hotel->getDestinationAsString();
            $arr["name"] = $hotel->getName();
            $arr["stars"] = $hotel->getStars();
            $arr["comments"] = $hotel->getComments();
            $arr["avgCost"] = $hotel->getAvgCost();
            $arr["streetAddress"] = $hotel->getStreetAddress();
            $arr["telephone"] = $hotel->getTelephone();
            $arr["email"] = $hotel->getEmail();
            $arr["hid"] = $hotel->getHid();
            $arr["url"] = $hotel->getUrl();
            $arr["inDB"] = $hotel->getInDB();
            $arr["deleted"] = $hotel->getDeleted();
            $json[] = $arr;
        }
        return json_encode($json);
    }

    function attractionsToJSON() {
        $json = array();
        foreach ($this->_attractions as $attraction) {
            $arr = array();
            $arr["key"] = $attraction->getDestinationAsString();
            $arr["name"] = $attraction->getName();
            $arr["type"] = $attraction->getType();
            $arr["comments"] = $attraction->getComments();
            $arr["streetAddress"] = $attraction->getStreetAddress();
            $arr["telephone"] = $attraction->getTelephone();
            $arr["email"] = $attraction->getEmail();
            $arr["url"] = $attraction->getUrl();
            $arr["aid"] = $attraction->getAid();
            $arr["inDB"] = $attraction->getInDB();
            $arr["deleted"] = $attraction->getDeleted();
            $json[] = $arr;
        }
        return json_encode($json);
    }

    function transportsToJSON() {
        $json = array();
        foreach ($this->_transports as $transport) {
            $arr = array();
            $arr["key"] = $transport->getDestinationAsString();
            $arr["name"] = $transport->getName();
            $arr["type"] = $transport->getType();
            $arr["avgCost"] = $transport->getAvgCost();
            $arr["streetAddress"] = $transport->getStreetAddress();
            $arr["telephone"] = $transport->getTelephone();
            $arr["email"] = $transport->getEmail();
            $arr["url"] = $transport->getUrl();
            $arr["transid"] = $transport->getTransid();
            $arr["inDB"] = $transport->getInDB();
            $arr["deleted"] = $transport->getDeleted();
            $json[] = $arr;
        }
        return json_encode($json);
    }

    function avoidsToJSON() {
        $json = array();
        foreach ($this->_avoids as $avoid) {
            $arr = array();
            $arr["key"] = $avoid->getDestinationAsString();
            $arr["name"] = $avoid->getName();
            $arr["type"] = $avoid->getType();
            $arr["reason"] = $avoid->getReason();
            $arr["time"] = $avoid->getTime();
            $arr["avid"] = $avoid->getAvid();
            $arr["deleted"] = $avoid->getDeleted();
            $arr["inDB"] = $avoid->getInDB();
            $json[] = $arr;
        }
        return json_encode($json);
    }

}

class TripDestination {
    const TABLE = "trip_destinations";
    const TABLE_DESTINATIONS = "destinations";

    private $_tid = NULL;
    private $_country = NULL;
    private $_state = NULL;
    private $_province = NULL;
    private $_city = NULL;
    private $_description = "";

    private $_key=NULL;
    private $_inDB=FALSE;
    private $_action = "add";
    private $_deleted=FALSE;

    function __construct($description) {
        $this->_description = $description;
    }

    // Serialize data of this instance to database.
    function toDB() {
        if ($this->_isValid()) {
            if ("add" == $this->_action) {
                // Check if destination exists in destinations table. If so we can use that as fk reference otherwise,
                // insert the destination.
                $query = "SELECT country,state,province,city FROM " . self::TABLE_DESTINATIONS .
                        " WHERE country='$this->_country' AND state='$this->_state' AND province='$this->_province'" .
                        " AND city='$this->_city'";
                $result = @mysql_query($query);
                if ($result) {
                    if (mysql_num_rows($result) == 0) {
                        // This destination is not yet in the destinations table. So let's insert it first.
                        $query = "INSERT INTO " . self::TABLE_DESTINATIONS .
                                " VALUES ('$this->_country', '$this->_state','$this->_province', '$this->_city')";
                        if (!@mysql_query($query)) {
                            return false;
                        }
                    }

                    // Now, we are sure that this destination is in destinations table.
                    $query = "INSERT INTO " . self::TABLE .
                            " VALUES ('$this->_tid','$this->_country','$this->_state','$this->_province', '$this->_city', '$this->_description')";
                    if (@mysql_query($query)) {
                        return true;
                    }
                }
            } else if ("update" == $this->_action) {
                // If key is as same as getDestinationAsString then it's good. Then update description only
                // Else check if if this new destination values exist in destinations table. if not insert them
                // (i.e. destination values) in destinations table.
                // Actually next should be done already by trip_design_helper ==>
                //  <after that change any X in this trip that has this key as destinationstring to this new destination values.>
                // check if this destination key is used by any other trip in trip_destinations. If not delete the
                // destination for this key from destinations table.finally update this entry in trip_destinations

                $destKeyAsArray = explode(',', $this->_key);
                if ($this->_key == $this->getDestinationAsString()) {
                    // Update description only.
                    $query = "UPDATE " . self::TABLE . " SET description='$this->_description' WHERE tid='$this->_tid' and country='$destKeyAsArray[0]' and state='$destKeyAsArray[1]' and province='$destKeyAsArray[2]' and city='$destKeyAsArray[3]'";
                    if (@mysql_query($query)) {
                        $this->_action = "none";
                        return true;
                    }
                } else {
                    // Here goes the hard stuff.

                    // Check if destination exists in destinations table. If so we can use that as fk reference otherwise,
                    // insert the destination.
                    $query = "SELECT country,state,province,city FROM " . self::TABLE_DESTINATIONS .
                            " WHERE country='$this->_country' AND state='$this->_state' AND province='$this->_province'" .
                            " AND city='$this->_city'";
                    $result = @mysql_query($query);
                    if ($result) {
                        if (mysql_num_rows($result) == 0) {
                            // This destination is not yet in the destinations table. So let's insert it first.
                            $query = "INSERT INTO " . self::TABLE_DESTINATIONS .
                                    " VALUES ('$this->_country', '$this->_state','$this->_province', '$this->_city')";
                            if (!@mysql_query($query)) {
                                return false;
                            }
                        }
                    } else {return false;}

                    // Mark as garbage the original destination if not referred by any trip in trip_destinations.
//                    $query = "SELECT * FROM " . self::TABLE . " WHERE tid<>'$this->_tid' and country='$destKeyAsArray[0]' and state='$destKeyAsArray[1]' and province='$destKeyAsArray[2]' and city='$destKeyAsArray[3]'";
//                    $result = @mysql_query($query);
//                    if ($result) {
//                        if (mysql_num_rows($result) == 0) {
//                            // Hmm this original destination is not referred by any other trip.
//                            // So just mark it to be deleted later;
//                            $this->_action="garbage";
//                        }
//
//                    } else {return false;}

                    // Now, if there's an entry in trip_destinations for this trip with the new destination values,
                    // that's not good. Let's assume it's handled by UI layer. Other option would be to update the
                    // description, but that's not intuitive to the user.
                    $query = "SELECT * FROM " . self::TABLE . " WHERE tid='$this->_tid' and country='$this->_country' and state='$this->_state' and province='$this->_province' and city='$this->_city'" ;
                    $result = @mysql_query($query);
                    if ($result){
                        if (mysql_num_rows($result) == 0) {
                            // Aha! Now it's OK to insert this new destinaton under this trip in trip_destinations
                            $query = "INSERT INTO " . self::TABLE .
                                    " VALUES ('$this->_tid', '$this->_country', '$this->_state','$this->_province', '$this->_city', '$this->_description')";
                            if (!@mysql_query($query)) {
                                return false;
                            }
                        } else {return false;}
                    } else {return false;}
                    $this->_action="garbage";
                    return true;
                }
            } else if ("delete" == $this->_action) {
                // Can't delete now as there may be depending Xs; Their action should be set to "delete" by UI by now.
                $this->_action = "garbage";
            } else if("none" == $this->_action) {
                // Do nothing.
                return true;
            } else if("garbage" == $this->_action) {
                // By now there can't be any Xs dependencies on this. Just make sure there are no references to this by
                // other trips in trip destinations table. If so delete this from destinations after deleting the entry
                // for this and tid from trip destinations;
                $destKeyAsArray = explode(',', $this->_key);
                $query = "SELECT * FROM " . self::TABLE . " WHERE tid<>'$this->_tid' and country='$destKeyAsArray[0]' and state='$destKeyAsArray[1]' and province='$destKeyAsArray[2]' and city='$destKeyAsArray[3]'";
                $result = @mysql_query($query);
                if ($result) {
                    if (mysql_num_rows($result) == 0) {
                        // Hmm this original destination is not referred by any other trip.
                        $query = "DELETE FROM " . self::TABLE . " WHERE tid='$this->_tid' and country='$destKeyAsArray[0]' and state='$destKeyAsArray[1]' and province='$destKeyAsArray[2]' and city='$destKeyAsArray[3]'";
                        if (@mysql_query($query)) {
                            // Good we just deleted the entry from trip_destinations table. Now, delete the destination from
                            // destinations table as well.
                            $query = "DELETE FROM " . self::TABLE_DESTINATIONS . " WHERE country='$destKeyAsArray[0]' and state='$destKeyAsArray[1]' and province='$destKeyAsArray[2]' and city='$destKeyAsArray[3]'";
                            if (@mysql_query($query)) {
                                // Good we are done.
                                return true;
                            }else{return false;}
                        } else {return false;}
                    } else {
                        // The original destination is referred by some other trip in trip_destinations.
                        // So we can't delete that destination from destinations. Only delete the entry in trip_destinations.
                        $query = "DELETE FROM " . self::TABLE . " WHERE tid='$this->_tid' and country='$destKeyAsArray[0]' and state='$destKeyAsArray[1]' and province='$destKeyAsArray[2]' and city='$destKeyAsArray[3]'";
                        if (@mysql_query($query)) {
                            // Good we just deleted the entry from trip_destinations and we are done now.
                            return true;
                        } else {
                            return false;
                        }
                    }
                } else {return false;}
            }
        }
        return false;
    }

    private function _isValid() {
        if (is_null($this->_tid) &&
                is_null($this->_country) &&
                is_null($this->_state) &&
                is_null($this->_province) &&
                is_null($this->_city)){
            return false;
        }
        return true;
    }

//    static function fromDB($tid, $country, $state, $province, $city) {
//        $query = "SELECT tid, country, state, province, city, description FROM " . self::TABLE . " WHERE tid='$tid' AND "
//                . " country='$country' AND state='$state' AND province='$province' AND city='$city';";
//        $result = @mysql_query($query);
//        if ($result) {
//            $row = mysql_fetch_assoc($result);
//            $tripDest = new TripDestination($row['description']);
//            $tripDest->setTid($row['tid']);
//            $tripDest->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
//
//            return $tripDest;
//        }
//        return false;
//    }

    // Get all TripDestinations from DB for a particular trip
    static function fromDB($tid) {
        $query = "SELECT * FROM " . self::TABLE . " WHERE tid='$tid'";
        $result = @mysql_query($query);
        if ($result) {
            $tripDestinations = array();
            while ($row = mysql_fetch_assoc($result)) {
                $tripDest = new TripDestination($row['description']);
                $tripDest->setTid($row['tid']);
                $tripDest->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
                $tripDest->setInDB(TRUE);
                $tripDest->setKey($tripDest->getDestinationAsString());
                $tripDestinations[$tripDest->getKey()] = $tripDest;
            }
            return $tripDestinations;
        }
        return false;
    }

    // Accessors
    function getTid() {return $this->_tid;}
    function getCountry() {return $this->_country;}
    function getState() {return $this->_state;}
    function getProvince() {return $this->_province;}
    function getCity() {return $this->_city;}
    function getDestinationAsString() {
        return $this->_country . "," . $this->_state . "," . $this->_province . "," . $this->_city ;
    }
    function getDescription() {return $this->_description;}

    function getKey(){return $this->_key;}
    function getInDB(){return $this->_inDB;}
    function getAction(){return $this->_action;}
    function getDeleted(){return $this->_deleted;}


    function setTid($value) {$this->_tid = $value;}
    function setCountry($value) {$this->_country = $value;}
    function setState($value) {$this->_state = $value;}
    function setProvince($value) {$this->_province = $value;}
    function setCity($value) {$this->_city = $value;}
        // Bulk setter for country,state,province,city
    function setDestination($country,$state,$province,$city) {
        $this->_country = $country;
        $this->_state = $state;
        $this->_province = $province;
        $this->_city = $city;
    }
    function setDescription($value) {$this->_description = $value;}

    function setKey($value) {$this->_key=$value;}
    function setInDB($value) {$this->_inDB = $value;}
    function setAction($value) {$this->_action = $value;}
    function setDeleted($value){$this->_deleted = $value;}

}

class Restaurant {
    const TABLE = "restaurants";

    private $_rid = NULL;
    private $_tid = NULL;
    private $_country = NULL;
    private $_state = NULL;
    private $_province = NULL;
    private $_city = NULL;
    private $_name = NULL;
    private $_type = "";
    private $_comments = "";
    private $_avg_cost = 0.0;
    private $_street_address = "";
    private $_telephone = "";
    private $_email = "";
    private $_url = "";

    private $_action="add";
    function getAction(){return $this->_action;}
    function setAction($value){$this->_action = $value;}

    private $_deleted=FALSE;
    function getDeleted(){return $this->_deleted;}
    function setDeleted($value){$this->_deleted = $value;}

    private $_inDB=FALSE;
    function getInDB(){return $this->_inDB;}
    function setInDB($value) {$this->_inDB = $value;}


    // Note. rid is not present in the constructor as it's auto generated.
    //       Also no foreign-keys in constructor. They should be set later.
    function __construct($name, $type, $comments, $avg_cost, $street_address,
                         $telephone, $email, $url) {
        $this->_name = $name;
        $this->_type = $type;
        $this->_comments = $comments;
        $this->_avg_cost = $avg_cost;
        $this->_street_address = $street_address;
        $this->_telephone = $telephone;
        $this->_email = $email;
        $this->_url = $url;
    }

    // Serialize data of this instance to database.
    function toDB() {
        if ($this->_isValid()) {
            if ("add" == $this->_action) {
                $query = "INSERT INTO " . self::TABLE . " VALUES (NULL,'$this->_tid', '$this->_country', '$this->_state',
                     '$this->_province', '$this->_city', '$this->_name', '$this->_type', '$this->_comments',
                     '$this->_avg_cost', '$this->_street_address', '$this->_telephone', '$this->_email', '$this->_url');";
                if (@mysql_query($query)) {
                    $this->_rid = mysql_insert_id();
                    return true;
                }
            } else if ("update" == $this->_action) {
                $query = "REPLACE INTO " . self::TABLE . " VALUES ('$this->_rid','$this->_tid', '$this->_country', '$this->_state',
                     '$this->_province', '$this->_city', '$this->_name', '$this->_type', '$this->_comments',
                     '$this->_avg_cost', '$this->_street_address', '$this->_telephone', '$this->_email', '$this->_url');";
                if (@mysql_query($query)) {
                    return true;
                }
            } else if ("delete" == $this->_action) {
                $query = "DELETE FROM " . self::TABLE . " WHERE rid='$this->_rid';";
                if (@mysql_query($query)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function _isValid() {
        if (is_null($this->_tid) &&
                is_null($this->_country) &&
                is_null($this->_state) &&
                is_null($this->_province) &&
                is_null($this->_city) &&
                is_null($this->_name)) {
            return false;
        }
        return true;
    }

//    static function fromDB($rid) {
//        $query = "SELECT rid, tid, country, state, province, city, name, type, comments, avg_cost," .
//                "street_address, telephone, email, url FROM " . self::TABLE . " WHERE rid=$rid;";
//        $result = @mysql_query($query);
//        if ($result) {
//            $row = mysql_fetch_assoc($result);
//            $r = new Restaurant($row['name'], $row['type'], $row['comments'], $row['avg_cost'],
//                $row['street_address'], $row['telephone'], $row['email'], $row['url']);
//            $r->setRid($row['rid']);
//            $r->setTid($row['tid']);
//            $r->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
//            return $r;
//        }
//        return false;
//    }

    // Get all Restaurants from DB for a particular trip
    static function fromDB($tid) {
        $query = "SELECT * FROM " . self::TABLE . " WHERE tid='$tid'";
        $result = @mysql_query($query);
        if ($result) {
            $restaurants = array();
            $r = NULL;
            while ($row = mysql_fetch_assoc($result)) {
                $r = new Restaurant($row['name'], $row['type'], $row['comments'], $row['avg_cost'],
                    $row['street_address'], $row['telephone'], $row['email'], $row['url']);
                $r->setRid($row['rid']);
                $r->setTid($row['tid']);
                $r->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
                $r->setInDB(TRUE);
                $r->setAction("update"); // Predict an update will happen if at least nothing
                $restaurants[$row['rid']] = $r;
            }
            return $restaurants;
        }
        return false;
    }

    // Accessors
    function getRid() {return $this->_rid;}
    function getTid() {return $this->_tid;}
    function getCountry() {return $this->_country;}
    function getState() {return $this->_state;}
    function getProvince() {return $this->_province;}
    function getCity() {return $this->_city;}
    function getDestinationAsString() {
        return $this->_country . "," . $this->_state . "," . $this->_province . "," . $this->_city ;
    }
    function getName() {return $this->_name;}
    function getType() {return $this->_type;}
    function getComments() {return $this->_comments;}
    function getAvgCost() {return $this->_avg_cost;}
    function getStreetAddress() {return $this->_street_address;}
    function getTelephone() {return $this->_telephone;}
    function getEmail() {return $this->_email;}
    function getUrl() {return $this->_url;}


    function setRid($value) { $this->_rid = $value;}
    function setTid($value) {$this->_tid = $value;}
    function setCountry($value) {$this->_country = $value;}
    function setState($value) {$this->_state = $value;}
    function setProvince($value) {$this->_province = $value;}
    function setCity($value) {$this->_city = $value;}
    // Bulk setter for country,state,province,city
    function setDestination($country,$state,$province,$city) {
        $this->_country = $country;
        $this->_state = $state;
        $this->_province = $province;
        $this->_city = $city;
    }
    function setName($value) {$this->_name = $value;}
    function setType($value) {$this->_type = $value;}
    function setComments($value) {$this->_comments = $value;}
    function setAvgCost($value) {$this->_avg_cost = $value;}
    function setStreetAddress($value) {$this->_street_address = $value;}
    function setTelephone($value) {$this->_telephone = $value;}
    function setEmail($value) {$this->_email = $value;}
    function setUrl($value) {$this->_url = $value;}

}

class Hotel {
    const TABLE = "hotels";

    private $_hid = NULL;
    private $_tid = NULL;
    private $_country = NULL;
    private $_state = NULL;
    private $_province = NULL;
    private $_city = NULL;
    private $_name = NULL;
    private $_stars = 0;
    private $_comments = "";
    private $_avg_cost = 0.0;
    private $_street_address = "";
    private $_telephone = "";
    private $_email = "";
    private $_url = "";

    private $_action="add";
    function getAction(){return $this->_action;}
    function setAction($value){$this->_action = $value;}

    private $_deleted=FALSE;
    function getDeleted(){return $this->_deleted;}
    function setDeleted($value){$this->_deleted = $value;}

    private $_inDB=FALSE;
    function getInDB(){return $this->_inDB;}
    function setInDB($value) {$this->_inDB = $value;}



    // Note. hid is not present in the constructor as it's auto generated.
    //       Also no foreign-keys in constructor. They should be set later.
    function __construct($name, $stars, $comments, $avg_cost, $street_address,
                         $telephone, $email, $url) {
        $this->_name = $name;
        $this->_stars = $stars;
        $this->_comments = $comments;
        $this->_avg_cost = $avg_cost;
        $this->_street_address = $street_address;
        $this->_telephone = $telephone;
        $this->_email = $email;
        $this->_url = $url;
    }

    // Serialize data of this instance to database.
    function toDB() {
        if ($this->_isValid()) {
            if ("add" == $this->_action) {
                $query = "INSERT INTO " . self::TABLE . " VALUES (NULL,'$this->_tid', '$this->_country', '$this->_state',
                     '$this->_province', '$this->_city', '$this->_name', '$this->_stars', '$this->_comments',
                     '$this->_avg_cost', '$this->_street_address', '$this->_telephone', '$this->_email', '$this->_url');";
                if (@mysql_query($query)) {
                    $this->_hid = mysql_insert_id();
                    return true;
                }
            } else if ("update" == $this->_action) {
                $query = "REPLACE INTO " . self::TABLE . " VALUES ('$this->_hid','$this->_tid', '$this->_country', '$this->_state',
                     '$this->_province', '$this->_city', '$this->_name', '$this->_stars', '$this->_comments',
                     '$this->_avg_cost', '$this->_street_address', '$this->_telephone', '$this->_email', '$this->_url');";
                if (@mysql_query($query)) {
                    return true;
                }
            } else if ("delete" == $this->_action) {
                $query = "DELETE FROM " . self::TABLE . " WHERE hid='$this->_hid';";
                if (@mysql_query($query)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function _isValid() {
        if (is_null($this->_tid) &&
                is_null($this->_country) &&
                is_null($this->_state) &&
                is_null($this->_province) &&
                is_null($this->_city) &&
                is_null($this->_name)) {
            return false;
        }
        return true;
    }

//    static function fromDB($hid) {
//        $query = "SELECT hid, tid, country, state, province, city, name, type, comments, avg_cost," .
//                "street_address, telephone, email, url FROM " . self::TABLE . " WHERE hid=$hid;";
//        $result = @mysql_query($query);
//        if ($result) {
//            $row = mysql_fetch_assoc($result);
//            $h = new Hotel($row['name'], $row['type'], $row['comments'], $row['avg_cost'],
//                $row['street_address'], $row['telephone'], $row['email'], $row['url']);
//            $h->setHid($row['rid']);
//            $h->setTid($row['tid']);
//            $h->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
//            return $h;
//        }
//        return false;
//    }

    // Get all Hotels from DB for a particular trip
    static function fromDB($tid) {
        $query = "SELECT * FROM " . self::TABLE . " WHERE tid='$tid'";
        $result = @mysql_query($query);
        if ($result) {
            $hotels = array();
            $h = NULL;
            while ($row = mysql_fetch_assoc($result)) {
                $h = new Hotel($row['name'], $row['stars'], $row['comments'], $row['avg_cost'],
                    $row['street_address'], $row['telephone'], $row['email'], $row['url']);
                $h->setHid($row['hid']);
                $h->setTid($row['tid']);
                $h->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
                $h->setInDB(TRUE);
                $h->setAction("update"); // Predict an update will happen if at least nothing
                $hotels[$row['hid']] = $h;
            }
            return $hotels;
        }
        return false;
    }

    // Accessors
    function getHid() {return $this->_hid;}
    function getTid() {return $this->_tid;}
    function getCountry() {return $this->_country;}
    function getState() {return $this->_state;}
    function getProvince() {return $this->_province;}
    function getCity() {return $this->_city;}
    function getDestinationAsString() {
        return $this->_country . "," . $this->_state . "," . $this->_province . "," . $this->_city ;
    }
    function getName() {return $this->_name;}
    function getStars() {return $this->_stars;}
    function getComments() {return $this->_comments;}
    function getAvgCost() {return $this->_avg_cost;}
    function getStreetAddress() {return $this->_street_address;}
    function getTelephone() {return $this->_telephone;}
    function getEmail() {return $this->_email;}
    function getUrl() {return $this->_url;}


    function setHid($value) { $this->_hid = $value;}
    function setTid($value) {$this->_tid = $value;}
    function setCountry($value) {$this->_country = $value;}
    function setState($value) {$this->_state = $value;}
    function setProvince($value) {$this->_province = $value;}
    function setCity($value) {$this->_city = $value;}
    // Bulk setter for country,state,province,city
    function setDestination($country,$state,$province,$city) {
        $this->_country = $country;
        $this->_state = $state;
        $this->_province = $province;
        $this->_city = $city;
    }
    function setName($value) {$this->_name = $value;}
    function setStars($value) {$this->_stars = $value;}
    function setComments($value) {$this->_comments = $value;}
    function setAvgCost($value) {$this->_avg_cost = $value;}
    function setStreetAddress($value) {$this->_street_address = $value;}
    function setTelephone($value) {$this->_telephone = $value;}
    function setEmail($value) {$this->_email = $value;}
    function setUrl($value) {$this->_url = $value;}
}


class Attraction {
    const TABLE = "attractions";

    private $_aid = NULL;
    private $_tid = NULL;
    private $_country = NULL;
    private $_state = NULL;
    private $_province = NULL;
    private $_city = NULL;
    private $_name = NULL;
    private $_type = "";
    private $_comments = "";
    private $_street_address = "";
    private $_telephone = "";
    private $_email = "";
    private $_url = "";

    private $_action="add";
    function getAction(){return $this->_action;}
    function setAction($value){$this->_action = $value;}

    private $_deleted=FALSE;
    function getDeleted(){return $this->_deleted;}
    function setDeleted($value){$this->_deleted = $value;}

    private $_inDB=FALSE;
    function getInDB(){return $this->_inDB;}
    function setInDB($value) {$this->_inDB = $value;}



    // Note. aid is not present in the constructor as it's auto generated.
    //       Also no foreign-keys in constructor. They should be set later.
    function __construct($name, $type, $comments, $street_address,
                         $telephone, $email, $url) {
        $this->_name = $name;
        $this->_type = $type;
        $this->_comments = $comments;
        $this->_street_address = $street_address;
        $this->_telephone = $telephone;
        $this->_email = $email;
        $this->_url = $url;
    }

    // Serialize data of this instance to database.
    function toDB() {
        if ($this->_isValid()) {
             if ("add" == $this->_action) {
                $query = "INSERT INTO " . self::TABLE . " VALUES (NULL,'$this->_tid', '$this->_country', '$this->_state',
                     '$this->_province', '$this->_city', '$this->_name', '$this->_type', '$this->_comments',
                     '$this->_street_address', '$this->_telephone', '$this->_email', '$this->_url');";
                if (@mysql_query($query)) {
                    $this->_aid = mysql_insert_id();
                    return true;
                }
            } else if ("update" == $this->_action) {
                $query = "REPLACE INTO " . self::TABLE . " VALUES ('$this->_aid','$this->_tid', '$this->_country', '$this->_state',
                     '$this->_province', '$this->_city', '$this->_name', '$this->_type', '$this->_comments',
                     '$this->_street_address', '$this->_telephone', '$this->_email', '$this->_url');";
                if (@mysql_query($query)) {
                    return true;
                }
            } else if ("delete" == $this->_action) {
                $query = "DELETE FROM " . self::TABLE . " WHERE aid='$this->_aid';";
                if (@mysql_query($query)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function _isValid() {
        if (is_null($this->_tid) &&
                is_null($this->_country) &&
                is_null($this->_state) &&
                is_null($this->_province) &&
                is_null($this->_city) &&
                is_null($this->_name)) {
            return false;
        }
        return true;
    }

//    static function fromDB($aid) {
//        $query = "SELECT aid, tid, country, state, province, city, name, type, comments, " .
//                "street_address, telephone, email, url FROM " . self::TABLE . " WHERE aid=$aid;";
//        $result = @mysql_query($query);
//        if ($result) {
//            $row = mysql_fetch_assoc($result);
//            $a = new Attraction($row['name'], $row['type'], $row['comments'],
//                $row['street_address'], $row['telephone'], $row['email'], $row['url']);
//            $a->setAid($row['aid']);
//            $a->setTid($row['tid']);
//            $a->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
//            return $a;
//        }
//        return false;
//    }

    // Get all Attractions from DB for a particular trip
    static function fromDB($tid) {
        $query = "SELECT * FROM " . self::TABLE . " WHERE tid='$tid'";
        $result = @mysql_query($query);
        if ($result) {
            $attractions = array();
            $a = NULL;
            while ($row = mysql_fetch_assoc($result)) {
                $a = new Attraction($row['name'], $row['type'], $row['comments'],
                    $row['street_address'], $row['telephone'], $row['email'], $row['url']);
                $a->setAid($row['aid']);
                $a->setTid($row['tid']);
                $a->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
                $a->setInDB(TRUE);
                $a->setAction("update"); // Predict an update will happen if at least nothing
                $attractions[$row['aid']] = $a;
            }
            return $attractions;
        }
        return false;
    }

    // Accessors
    function getAid() {return $this->_aid;}
    function getTid() {return $this->_tid;}
    function getCountry() {return $this->_country;}
    function getState() {return $this->_state;}
    function getProvince() {return $this->_province;}
    function getCity() {return $this->_city;}
    function getDestinationAsString() {
        return $this->_country . "," . $this->_state . "," . $this->_province . "," . $this->_city ;
    }
    function getName() {return $this->_name;}
    function getType() {return $this->_type;}
    function getComments() {return $this->_comments;}
    function getStreetAddress() {return $this->_street_address;}
    function getTelephone() {return $this->_telephone;}
    function getEmail() {return $this->_email;}
    function getUrl() {return $this->_url;}


    function setAid($value) { $this->_aid = $value;}
    function setTid($value) {$this->_tid = $value;}
    function setCountry($value) {$this->_country = $value;}
    function setState($value) {$this->_state = $value;}
    function setProvince($value) {$this->_province = $value;}
    function setCity($value) {$this->_city = $value;}
     // Bulk setter for country,state,province,city
    function setDestination($country,$state,$province,$city) {
        $this->_country = $country;
        $this->_state = $state;
        $this->_province = $province;
        $this->_city = $city;
    }
    function setName($value) {$this->_name = $value;}
    function setType($value) {$this->_type = $value;}
    function setComments($value) {$this->_comments = $value;}
    function setStreetAddress($value) {$this->_street_address = $value;}
    function setTelephone($value) {$this->_telephone = $value;}
    function setEmail($value) {$this->_email = $value;}
    function setUrl($value) {$this->_url = $value;}
}

class Transport {
    const TABLE = "transports";

    private $_transid = NULL;
    private $_tid = NULL;
    private $_country = NULL;
    private $_state = NULL;
    private $_province = NULL;
    private $_city = NULL;
    private $_name = NULL;
    private $_type = "";
    private $_avg_cost = 0.0;
    private $_street_address = "";
    private $_telephone = "";
    private $_email = "";
    private $_url = "";

    private $_action="add";
    function getAction(){return $this->_action;}
    function setAction($value){$this->_action = $value;}

    private $_deleted=FALSE;
    function getDeleted(){return $this->_deleted;}
    function setDeleted($value){$this->_deleted = $value;}

    private $_inDB=FALSE;
    function getInDB(){return $this->_inDB;}
    function setInDB($value) {$this->_inDB = $value;}



    // Note. transid is not present in the constructor as it's auto generated.
    //       Also no foreign-keys in constructor. They should be set later.
    function __construct($name, $type, $avg_cost, $street_address,
                         $telephone, $email, $url) {
        $this->_name = $name;
        $this->_type = $type;
        $this->_avg_cost = $avg_cost;
        $this->_street_address = $street_address;
        $this->_telephone = $telephone;
        $this->_email = $email;
        $this->_url = $url;
    }

    // Serialize data of this instance to database.
    function toDB() {
        if ($this->_isValid()) {
            if ("add" == $this->_action) {
                $query = "INSERT INTO " . self::TABLE . " VALUES (NULL,'$this->_tid', '$this->_country', '$this->_state',
                     '$this->_province', '$this->_city', '$this->_name', '$this->_type',
                     '$this->_avg_cost', '$this->_street_address', '$this->_telephone', '$this->_email', '$this->_url');";
                if (@mysql_query($query)) {
                    $this->_transid = mysql_insert_id();
                    return true;
                }
            } else if ("update" == $this->_action) {
                $query = "REPLACE INTO " . self::TABLE . " VALUES ('$this->_transid','$this->_tid', '$this->_country', '$this->_state',
                     '$this->_province', '$this->_city', '$this->_name', '$this->_type',
                     '$this->_avg_cost', '$this->_street_address', '$this->_telephone', '$this->_email', '$this->_url');";
                if (@mysql_query($query)) {
                    return true;
                }
            } else if ("delete" == $this->_action) {
                $query = "DELETE FROM " . self::TABLE . " WHERE transid='$this->_transid';";
                if (@mysql_query($query)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function _isValid() {
        if (is_null($this->_tid) &&
                is_null($this->_country) &&
                is_null($this->_state) &&
                is_null($this->_province) &&
                is_null($this->_city) &&
                is_null($this->_name)) {
            return false;
        }
        return true;
    }

    // Get all Transports from DB for a particular trip
    static function fromDB($tid) {
        $query = "SELECT * FROM " . self::TABLE . " WHERE tid='$tid'";
        $result = @mysql_query($query);
        if ($result) {
            $transports = array();
            $tr = NULL;
            while ($row = mysql_fetch_assoc($result)) {
                $tr = new Transport($row['name'], $row['type'], $row['avg_cost'],$row['street_address'],
                    $row['telephone'], $row['email'], $row['url']);
                $tr->setTransid($row['transid']);
                $tr->setTid($row['tid']);
                $tr->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
                $tr->setInDB(TRUE);
                $tr->setAction("update"); // Predict an update will happen if at least nothing
                $transports[$row['transid']] = $tr;
            }
            return $transports;
        }
        return false;
    }

    // Accessors
    function getTransid() {return $this->_transid;}
    function getTid() {return $this->_tid;}
    function getCountry() {return $this->_country;}
    function getState() {return $this->_state;}
    function getProvince() {return $this->_province;}
    function getCity() {return $this->_city;}
    function getDestinationAsString() {
        return $this->_country . "," . $this->_state . "," . $this->_province . "," . $this->_city ;
    }
    function getName() {return $this->_name;}
    function getType() {return $this->_type;}
    function getAvgCost() {return $this->_avg_cost;}
    function getStreetAddress() {return $this->_street_address;}
    function getTelephone() {return $this->_telephone;}
    function getEmail() {return $this->_email;}
    function getUrl() {return $this->_url;}


    function setTransid($value) { $this->_transid = $value;}
    function setTid($value) {$this->_tid = $value;}
    function setCountry($value) {$this->_country = $value;}
    function setState($value) {$this->_state = $value;}
    function setProvince($value) {$this->_province = $value;}
    function setCity($value) {$this->_city = $value;}
    // Bulk setter for country,state,province,city
    function setDestination($country,$state,$province,$city) {
        $this->_country = $country;
        $this->_state = $state;
        $this->_province = $province;
        $this->_city = $city;
    }
    function setName($value) {$this->_name = $value;}
    function setType($value) {$this->_type = $value;}
    function setAvgCost($value) {$this->_avg_cost = $value;}
    function setStreetAddress($value) {$this->_street_address = $value;}
    function setTelephone($value) {$this->_telephone = $value;}
    function setEmail($value) {$this->_email = $value;}
    function setUrl($value) {$this->_url = $value;}
}

class Avoid {
    const TABLE = "avoids";

    private $_avid = NULL;
    private $_tid = NULL;
    private $_country = NULL;
    private $_state = NULL;
    private $_province = NULL;
    private $_city = NULL;
    private $_name = NULL;
    private $_type = "";
    private $_reason = "";
    private $_time = "";

    private $_action="add";
    function getAction(){return $this->_action;}
    function setAction($value){$this->_action = $value;}

    private $_deleted=FALSE;
    function getDeleted(){return $this->_deleted;}
    function setDeleted($value){$this->_deleted = $value;}

    private $_inDB=FALSE;
    function getInDB(){return $this->_inDB;}
    function setInDB($value) {$this->_inDB = $value;}


    // Note. avid is not present in the constructor as it's auto generated.
    //       Also no foreign-keys in constructor. They should be set later.
    function __construct($name, $type, $reason, $time){
        $this->_name = $name;
        $this->_type = $type;
        $this->_reason = $reason;
        $this->_time = $time;
    }

    // Serialize data of this instance to database.
    function toDB() {
        if ($this->_isValid()) {
            if ("add" == $this->_action) {
                $query = "INSERT INTO " . self::TABLE . " VALUES (NULL,'$this->_tid', '$this->_country', '$this->_state',
                     '$this->_province', '$this->_city', '$this->_name', '$this->_type',
                     '$this->_reason', '$this->_time');";
                if (@mysql_query($query)) {
                    $this->_avid = mysql_insert_id();
                    return true;
                }
            } else if ("update" == $this->_action) {
                $query = "REPLACE INTO " . self::TABLE . " VALUES ('$this->_avid','$this->_tid', '$this->_country', '$this->_state',
                     '$this->_province', '$this->_city', '$this->_name', '$this->_type',
                     '$this->_reason', '$this->_time');";
                if (@mysql_query($query)) {
                    return true;
                }
            } else if ("delete" == $this->_action) {
                $query = "DELETE FROM " . self::TABLE . " WHERE avid='$this->_avid';";
                if (@mysql_query($query)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function _isValid() {
        if (is_null($this->_tid) &&
                is_null($this->_country) &&
                is_null($this->_state) &&
                is_null($this->_province) &&
                is_null($this->_city) &&
                is_null($this->_name)) {
            return false;
        }
        return true;
    }

//    static function fromDB($avid) {
//        $query = "SELECT avid, tid, country, state, province, city, name, type, reason, time FROM " .
//                self::TABLE . " WHERE avid=$avid;";
//        $result = @mysql_query($query);
//        if ($result) {
//            $row = mysql_fetch_assoc($result);
//            $av = new Avoid($row['name'], $row['type'], $row['reason'], $row['time']);
//            $av->setAvid($row['avid']);
//            $av->setTid($row['tid']);
//            $av->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
//            return $av;
//        }
//        return false;
//    }


    // Get all Avoids from DB for a particular trip
    static function fromDB($tid) {
        $query = "SELECT * FROM " . self::TABLE . " WHERE tid='$tid'";
        $result = @mysql_query($query);
        if ($result) {
            $avoids = array();
            $av = NULL;
            while ($row = mysql_fetch_assoc($result)) {
                $av = new Avoid($row['name'], $row['type'], $row['reason'], $row['time']);
                $av->setAvid($row['avid']);
                $av->setTid($row['tid']);
                $av->setDestination($row['country'], $row['state'], $row['province'],$row['city']);
                $av->setInDB(TRUE);
                $av->setAction("update"); // Predict an update will happen if at least nothing
                $avoids[$row['avid']] = $av;
            }
            return $avoids;
        }
        return false;
    }

    // Accessors
    function getAvid() {return $this->_avid;}
    function getTid() {return $this->_tid;}
    function getCountry() {return $this->_country;}
    function getState() {return $this->_state;}
    function getProvince() {return $this->_province;}
    function getCity() {return $this->_city;}
    function getDestinationAsString() {
        return $this->_country . "," . $this->_state . "," . $this->_province . "," . $this->_city ;
    }
    function getName() {return $this->_name;}
    function getType() {return $this->_type;}
    function getReason() {return $this->_reason;}
    function getTime() {return $this->_time;}


    function setAvid($value) { $this->_avid = $value;}
    function setTid($value) {$this->_tid = $value;}
    function setCountry($value) {$this->_country = $value;}
    function setState($value) {$this->_state = $value;}
    function setProvince($value) {$this->_province = $value;}
    function setCity($value) {$this->_city = $value;}
    // Bulk setter for country,state,province,city
    function setDestination($country,$state,$province,$city) {
        $this->_country = $country;
        $this->_state = $state;
        $this->_province = $province;
        $this->_city = $city;
    }
    function setName($value) {$this->_name = $value;}
    function setType($value) {$this->_type = $value;}
    function setReason($value) {$this->_reason = $value;}
    function setTime($value) {$this->_time = $value;}
}