<?php

if(isset($_GET['choosen_route']))
{
	//IMPORTING REQUIRED MODULES
	require_once 'include/config.php';
	require_once 'requests.php';
	require_once 'sanitize.php';
	require_once 'get_trips.php';
	
	$conn = new mysqli($hostname, $username, $password, $database);
	
	//Sanitize mysql and strings from get request of index.php
    $route_short_name= sanitizeMySQL($conn, $_GET['choosen_route']); 
	
	if ($conn->connect_error) die($conn->connect_error);
	
	$trip_ids = get_trips($conn, $route_short_name);
	
	$url = "https://api.at.govt.nz/v2/public/realtime/vehiclelocations";
	// Query parameters for AUCKLAND API, needs to be stored in an array
	$params = array('tripid' => $trip_ids);	
	
	$results = apiCall($APIKey, $url, $params);
	// Tell the browser we are sending back json
	header('Content-Type: application/json');
	
	//Echo out the result into HTML/JavaScript to be captured
	echo json_encode($results);
	$conn->close();
}

?>

