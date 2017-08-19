<?php //query.php
require_once 'include/common.php';
require_once 'include/config.php';
require_once 'database.php';
require_once 'requests.php';

set_time_limit(500);

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

    $trip_ids = prepraredQuery($conn, $query, $fmt, $values);

    $results = array();
    $start_query = "https://api.at.govt.nz/v2/public/realtime/vehiclelocations?tripid=";
    $query = $start_query;
    foreach($trip_ids as $key => $value)
    {
        if ($len = strlen($query) > 1800)
        {
            $results[] = rtrim($query, ',');
            $query = $start_query;
        }
        $query .= $value['trip_id'] . ",";
    }
    $query = rtrim($query, ',');
    $results[] = $query;
    $getter = new ParallelGet($results, $APIKey);
    $getter->execute();
    header('Content-Type: application/json');
    echo json_encode($getter->getResults());
}

$conn->close();
?>
