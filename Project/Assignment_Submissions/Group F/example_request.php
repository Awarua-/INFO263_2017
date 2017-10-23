<?php //database.php
require_once 'include/config.php';
require_once 'requests.php';

$url = "https://api.at.govt.nz/v2/public/realtime/vehiclelocations";
# if we had query parametets say, trip_ids, we would include an array of them like below

$trip_ids = array();

$db=new mysqli($hostname,"root","root",$database);
if(mysqli_connect_error()){
    echo 'Could not connect to database.';
    exit;
}
$result=$db->query("SELECT  *  FROM routes");
$row = $result->fetch_all();
for($i = 0 ; $i < count($row); $i++){
    $trip_ids[$i] = $row[$i]["route_id"];
}



$params = array("tripid" => $trip_ids);
//$params = array();
$results = apiCall($APIKey, $url, $params);
// Tell the browser we are sending back json
header('Content-Type: application/json');
print json_encode($results);

?>
