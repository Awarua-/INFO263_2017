<?php
    require_once 'include/config.php';
    require_once 'requests.php';

    $url = "https://api.at.govt.nz/v2/public/realtime/vehiclelocations";
    $id = !empty($_POST["id"]) ? $_POST["id"]: null; 
    $trip_ids = array();
    $db=new mysqli($hostname,$username,$password,$database);
    if(mysqli_connect_error())
    {
        echo 'Could not connect to database.';
        exit;
    }
    $route_id = $db->query("SELECT route_id FROM routes where route_short_name = '$id'")->fetch_all();
    for($i = 0 ; $i < count($route_id); $i++)
    {
        $route_t_id = $route_id[$i][0];
        $tmp = $db->query("SELECT trip_id FROM trips where route_id = '$route_t_id'")->fetch_all();
        if(count($tmp) != 0)
        {
            $trip_ids[$i] = $tmp[0][0];
        }
    }
    $params = array("tripid" => $trip_ids);
    $results = apiCall($APIKey, $url, $params);
    header('Content-Type: application/json');
    echo json_encode($results);
?>
