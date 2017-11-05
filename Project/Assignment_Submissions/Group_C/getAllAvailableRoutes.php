<?php
require_once 'request_data.php';


/**
 * This function returns all the available routes. Each route contains some information. Read the
 * @return array below for a bit more detail.
 *
 * @param string $APIKey   API key for using, Auckland Transport API
 * @param string $url      Url, API end point.
 * @param mysqli $conn     A connection to a mysql database
 * @return array           Returns an associative array. The first item in the array is the route_id (key).
 *                         The second item is the info (key); the values are as follows; route_short_name,
 *                         route_long_name, route_type and agency_id.
 */
function getAllValidRouteInfo($APIKey, $url, $conn) {
    $allValidRoutes = getAllTrips($APIKey, $url);
    $allValidRouteInformation = array();

    foreach ($allValidRoutes as $key => $value) {
        $allValidRouteInformation[] = getRouteInfo($conn, $key);
    }
    return $allValidRouteInformation;
}

$result = getAllValidRouteInfo($APIKey, $url, $conn);
header('Content-Type: application/json');
print(json_encode($result));

?>