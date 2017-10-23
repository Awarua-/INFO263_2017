<?php
	require_once 'include/config.php';
	require_once 'requests.php';
	$url = "https://api.at.govt.nz/v2/public/realtime/vehiclelocations";
    $conn = new mysqli($hostname, $username, $password, $database); //build a new connection to the database
	if ($conn->connect_error) die($conn->connect_error);
	$item = $_POST['route_name'];
	$query = "SELECT trip_id FROM trips, routes WHERE route_short_name='" . $item . "' AND trips.route_id = routes.route_id"; //Select data from the database where it is identical with $item so that the data will be shown on the map correctly
	$dbRoute=$conn->query($query);
	$trips = array();

	while ($row = mysqli_fetch_array($dbRoute)) {
		$trip_id=array();
		$trip_id = $row['trip_id'];
		array_push($trips, $trip_id);
	}

	$params = array("tripid" => $trips);
	$results = apiCall($APIKey, $url, $params);

	$get_info = array();
    $bus_info = array();
    
    # Codes for returning bus information on the map
	foreach ($results as $result) {
		$item = json_decode($result);
		if(!empty($item->response)) {
			foreach ($item->response->entity as $i) {
				$bus_info += ["id" => $i->vehicle->vehicle->id];
				$bus_info += ["start_time" => $i->vehicle->trip->start_time];
				$bus_info += ["timestamp" => $i->vehicle->timestamp];
                $bus_info += ["latitude" => $i->vehicle->position->latitude];
                $bus_info += ["longitude" => $i->vehicle->position->longitude];
                
				array_push($get_info, $bus_info);
                $bus_info = array();
			}
		}
	}
	$results = json_encode($get_info);
	echo $results;
	
?>