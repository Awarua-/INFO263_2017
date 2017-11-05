<?php //database.php
require_once 'include/config.php';
require_once 'requests.php';
$routename = $_GET['q'];
$conn = new mysqli($hostname, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getActiveVehicles($conn,$routename){
    $params = array();
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    $query = 'SELECT trip_id from `akl_transport`.`trips` WHERE route_id = ANY(SELECT route_id FROM `akl_transport`.`routes` WHERE route_short_name ="'.$routename.'")';
    $result = $conn->query($query);
    if (!$result) die ($conn->error);
    while($row = mysqli_fetch_array($result)){
       $params[] = $row['trip_id'];
    } 

   // $conn->close();
    
    $url ="https://api.at.govt.nz/v2/public/realtime/vehiclelocations";
    $APIKey = '81fd53734c494d1994bc1236585320c5';

    $querystring = array("tripid" => $params);

    $results = apiCall($APIKey, $url,$querystring);   
    header('Content-Type: application/json');
    return $results;
    
}
$result = getActiveVehicles($conn,$routename);
print $result[0];


?>