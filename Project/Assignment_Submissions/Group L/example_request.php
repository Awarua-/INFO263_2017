<?php //database.php
require_once 'include/config.php';
require_once 'requests.php';
require_once 'database.php';

$url = "https://api.at.govt.nz/v2/public/realtime/vehiclelocations";

$database=new Database($hostname, $username, $password, $database);

header('Content-Type: application/json');

/**
 * Get all routes
 */
if (array_key_exists('GetAllRoutes', $_GET)) {
    print $database->getAll();
}

/**
 * Get marks through AT Api
 */
if (array_key_exists('GetMarksByRouteId', $_GET)) {
    
    $trips = json_decode($database->getTripsByRouteId($_GET['RouteId']));

    $trip_ids=[];
    foreach ($trips as $trip) {
        $trip_ids[]=$trip->trip_id;
    }

    $params=array();
    $params = array("tripid" => $trip_ids);
    $apiResults = apiCall($APIKey, $url, $params);
    
    $response=json_decode($apiResults[0], true)['response'];

    //clean the response
    if (array_key_exists('entity', $response)) {
        $result=[];
        
        $positions=[];
        
        $entities=$response['entity'];
    
        foreach ($entities as $entity) {
            if (array_key_exists('vehicle', $entity)) {
                $vehicle=[];
                $vehicle['position']=$entity['vehicle']['position'];
                $vehicle['trip']=$entity['vehicle']['trip'];
                $vehicle['vehicleId']=$entity['vehicle']['vehicle']['id'];
                $result[]=$vehicle;
            }
        }
        print json_encode($result);
    } else {
        print "null";
    }
}
