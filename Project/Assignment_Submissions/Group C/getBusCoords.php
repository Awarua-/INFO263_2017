<?php
require_once 'request_data.php';

// gets route ids and uses the returned data to get the vehicle positions from the database
if (isset($_GET['route_id'])) {
    $route_id = array($_GET['route_id']);
    $allRoutes = getAllTrips($APIKey, $url);
    $positions = getVehicleLocations($allRoutes[$route_id[0]], $APIKey, $url);

    header('Content-Type: application/json');
    print(json_encode($positions));
}

?>