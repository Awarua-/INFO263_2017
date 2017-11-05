<?php
require_once "include/config.php";
require_once 'requests.php';
require_once 'routes.php';


// Example (uncomment to test) /////////////////////////////////////////////////////////////
//$allRoutes = getAllTrips($APIKey, $url);
//print_r($allRoutes["24912-20170918164808_v58.16"]);
//print_r($allRoutes);

//$info = getVehicleLocations($allRoutes["22105-20170918164808_v58.16"], $APIKey, $url);
//print_r($info);
////////////////////////////////////////////////////////////////////////////////////////////


/**
 * This function retrieves all the *available* routes from the Auckland Transport API.
 *
 * @param string $APIKey    API key for using, Auckland Transport API
 * @param string $url       Url, API end point.
 * @return array            Associative array with the route_id as the key and the value as an array of its trip_ids.
 */
function getAllTrips($APIKey, $url)
{
    $trips = array(); // associative array
    $params = array();
    $results = apiCall($APIKey, $url, $params);
    // Tell the browser we are sending back json
    header('Content-Type: application/json');
    $routes = json_decode($results[0]);

    for ($i = 0; $i < count($routes->response->entity); $i++) {
    	if (!empty($routes->response->entity[$i]->trip_update->trip->route_id) &&
    		!empty($routes->response->entity[$i]->trip_update->trip->trip_id)) {
	        $route_id = $routes->response->entity[$i]->trip_update->trip->route_id;
	        $trip_id = $routes->response->entity[$i]->trip_update->trip->trip_id;

	        if (!empty($route_id)) {
	            if (array_key_exists($route_id, $trips)) {
	                $trips[$route_id][] = $trip_id;
	            } else {
	                $trips[$route_id] = array($trip_id);
	            }
	        }
        }
    }

    return $trips;
}


/**
 * This function retrieves the coordinates (latitude and longitude) and vehicle information for each trip in the array. The trip_ids
 * must be *valid/available* as the Auckland Transport API only accepts valid/available trips.
 *
 * @param array $trip_ids   An array of the trip_ids (as a string)
 * @param string $APIKey    API key for using, Auckland Transport API
 * @param string $url       Url, API end point.
 * @return array            Associative array with the key as the trip id and value as an associative array that contains
 *                          the latitude, longitude, trip id, stop id, vehicle id, arrival and delay time or departure or delay time,
 *                          and occupancy status.
 */
function getVehicleLocations($trip_ids, $APIKey, $url)
{
    $info = array(); // associative array
    $params = array("tripid" => $trip_ids);
    $results = apiCall($APIKey, $url, $params);
    // Tell the browser we are sending back json
    header('Content-Type: application/json');
    $vehicles = json_decode($results[0]);

    for ($i = 0; $i < count($vehicles->response->entity); $i++) {
        if (!empty($vehicles->response->entity[$i]->trip_update->trip->trip_id)) {
            $info_trip_id = $vehicles->response->entity[$i]->trip_update->trip->trip_id;
            $stop_id = $vehicles->response->entity[$i]->trip_update->stop_time_update->stop_id;
            $vehicle_id = $vehicles->response->entity[$i]->trip_update->vehicle->id;

            if (!array_key_exists($info_trip_id, $info)) {
                $info[$info_trip_id] = array();
                $info[$info_trip_id]["trip_id"] = $info_trip_id;
            }

            if (!empty($vehicles->response->entity[$i]->trip_update->stop_time_update->arrival)) {  // arrivals
                $arrival_delay = $vehicles->response->entity[$i]->trip_update->stop_time_update->arrival->delay;
                $arrival_time = $vehicles->response->entity[$i]->trip_update->stop_time_update->arrival->time;

                $info[$info_trip_id]["arrival_delay"] = $arrival_delay;
                $info[$info_trip_id]["arrival_time"] = $arrival_time;
            } else {    // departures
                $departure_delay = $vehicles->response->entity[$i]->trip_update->stop_time_update->departure->delay;
                $depature_time = $vehicles->response->entity[$i]->trip_update->stop_time_update->departure->time;

                $info[$info_trip_id]["arrival_delay"] = $departure_delay;
                $info[$info_trip_id]["arrival_time"] = $depature_time;
            }

            $info[$info_trip_id]["stop_id"] = $stop_id;
            $info[$info_trip_id]["vehicle_id"] = $vehicle_id;
        }

        if (!empty($vehicles->response->entity[$i]->vehicle->position->latitude) &&
            !empty($vehicles->response->entity[$i]->vehicle->position->longitude)) {
            $latitude = $vehicles->response->entity[$i]->vehicle->position->latitude;
            $longitude = $vehicles->response->entity[$i]->vehicle->position->longitude;
            $location_trip_id = $vehicles->response->entity[$i]->vehicle->trip->trip_id;
            $occupancy_status = $vehicles->response->entity[$i]->vehicle->occupancy_status;

            if (!array_key_exists($location_trip_id, $info)) {
                $info[$location_trip_id] = array();
                $info[$location_trip_id]["trip_id"] = $location_trip_id;
            }

            $info[$info_trip_id]["latitude"] = $latitude;
            $info[$info_trip_id]["longitude"] = $longitude;
            $info[$info_trip_id]["occupancy_status"] = $occupancy_status;
        }
    }

    return $info;
}

?>