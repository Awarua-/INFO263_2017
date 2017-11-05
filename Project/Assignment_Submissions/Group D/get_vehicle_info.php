<?php
if (isset($_POST['route_short_name']))
{

    require_once "include/functions.php";
    require_once "requests.php";
    require_once "include/config.php";

    $route_short_name = $_POST['route_short_name'];
    $trip_ids = get_trip_ids(get_route_ids($route_short_name));

    $results = apiCall($APIKey, "https://api.at.govt.nz/v2/public/realtime/vehiclelocations", array("tripid" => $trip_ids));

    $vehicles = array();
    for ($i = 0; $i < sizeof($results); $i++) {
        $out = vehicle_json_parser(json_decode($results[$i], true));
        if (sizeof($out)> 0) {
            $vehicles = array_merge($vehicles, $out);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($vehicles);
}
?>