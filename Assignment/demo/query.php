<?php //query.php
require_once 'include/common.php';
require_once 'include/config.php';
require_once 'database.php';
require_once 'requests.php';

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

if (isset($_POST['query']))
{
    $query_type = mysqlSanitise($conn, $_POST['query']);
    switch($query_type)
    {
        case "routes":
            $query = "SELECT distinct route_short_name FROM routes ORDER BY route_short_name;";
            echo json_encode(query($conn, $query));
            break;
        case "route_lookup":
            $name = $_POST['data'];
            route_lookup($conn, $name, $APIKey);
            break;
        default:
            $query = NULL;
    }
}

function route_lookup($conn, $data, $APIKey)
{
    $query = "SELECT trip_id FROM routes JOIN trips USING(route_id) WHERE route_short_name = ?;";
    $fmt = "s";
    $values = array(mysqlSanitise($conn, $data));

    $result = prepraredQuery($conn, $query, $fmt, $values);
    $trip_ids = array();
    foreach ($result as $index => $value)
    {
        $trip_ids[] = $value['trip_id'];
    }
    $url = "https://api.at.govt.nz/v2/public/realtime/vehiclelocations";
    $params = array("tripid" => $trip_ids);
    $results = apiCall($APIKey, $url, $params);
    $data = json_decode($results, true);

    $out = array();
    foreach ($data as $value)
    {
        $value = json_decode($value, true);
        if (!empty($value['response']))
        {
            $entities = $value['response']['entity'];
            foreach ($entities as $entity)
            {
                $vehicle = $entity['vehicle'];
                $vehicle_data = array('id' => $vehicle['vehicle']['id'], 'lat' => $vehicle['position']['latitude'], 'lng' => $vehicle['position']['longitude']);
                $trip = $vehicle['trip'];
                $timestamp = $vehicle['timestamp'];

                $result = array('vehicle' => $vehicle_data, 'trip' => $trip, 'timestamp' => $timestamp);
                $out[] = $result;
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode($out);
}

$conn->close();
?>
