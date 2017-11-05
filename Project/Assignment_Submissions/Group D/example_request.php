<?php //database.php
# if we had query parametets say, trip_ids, we would include an array of them like below
# $trip_ids = array(1, 3, 64);
# $params = array("tripid" => $trip_ids);
function runApiCall($trip_ids)
{
    include 'include/config.php';
    require_once 'requests.php';
    $params = array("tripid" => $trip_ids);
    $url = "https://api.at.govt.nz/v2/public/realtime/vehiclelocations";
    $results = apiCall($APIKey, $url, $params);
    // Tell the browser we are sending back json
    header('Content-Type: application/json');
    return $results[0];
}
?>
