<?php 
// This is the vehicle_query.php file for INFO263 by GroupDev N

require_once 'include/config.php';
require_once 'requests.php';
require_once 'database.php';

/**
function used to take in json format input of apicall results and decode each element to find the id, longitude
and latitude of each trip on a route.
@param $json json encoded array of results generated from the apiCall function
@return array $allBuses array of decoded json results containing ids, longitude and latitude locations
**/
function getBusInfo($json)
{
	$allBusses = array();
	
	# loop to iterate all trips on a route
	for($i = 0; $i < count($json); $i++){
		$busData = json_decode($json[$i]);
		if (is_object($busData->response)) {
			$busInfo = $busData->response->entity;
			# iterate through busses on the trip
			for($j = 0; $j < count($busInfo); $j++) {
				$id = $busInfo[$j]->vehicle->vehicle->id;
				$longitude = $busInfo[$j]->vehicle->position->longitude;
				$latitude = $busInfo[$j]-> vehicle->position->latitude;
				
				//push each variable into the associative array with pair values
				array_push($allBusses, array('id' => $id, 'longitude' => $longitude, 'latitude' => $latitude));	
			}
		}
	}
	return($allBusses);
}

/**
this code is executed when an ajax request is sent to vehicle_query.php from map.js
checks to see if the request has been set with an input from the user and assigns it a variable param.
executes the processJSON function and echos the results back to map.js for the map markers to be displayed
**/
if(isset($_REQUEST["r"]))
{
	$param = $_REQUEST["r"];
	$trip_ids = getTripIds($conn, $param);
	$conn->close();
	$params = array("tripid" => $trip_ids);
	$apiJSON = apiCall($APIKey, $url, $params);
	$busArray = getBusInfo($apiJSON);
	$busArrayJSON = json_encode($busArray);
	header('Content-Type: application/json');
	echo $busArrayJSON ;
}

?>