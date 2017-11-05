	<?php //database.php
	require_once 'include/config.php';
	require_once 'requests.php';
	
	
	$conn = new mysqli($hostname, $username, $password, $database); 
	if($conn->connect_error)
	{
		return;
	}

	
	if (isset($_POST['route']) && !empty($_POST['route'])) {
		$sel = $_POST['route'];
		$route = sanitizeInput($conn, $sel); //sanitize data
		
		//using prepared query
		if($stmt = $conn->prepare("select t.trip_id, r.route_long_name from routes r join trips t on r.route_id = t.route_id where r.route_short_name = ?")) {
			$stmt->bind_param('s', $route);
			$stmt->execute();
			
			$trip_ids = array();
			
			$result = $stmt->get_result();
			while($row = $result->fetch_assoc()) { 
				array_push($trip_ids, $row['trip_id']); 
				$route_name  = $row['route_long_name'];
			}
			
			if (count($trip_ids) == 0) {
				//return array();
				return;
			}
		}
		else { //if prepared query fails return
			return;
		}
		
		$url = "https://api.at.govt.nz/v2/public/realtime/vehiclelocations";
		
		$params = array("tripid" => $trip_ids);
		$results = apiCall($APIKey, $url, $params); //query the Auckland API
		header('Content-Type: application/json'); //telling the browser we are sending back json
		$vehicles = array();

		
		foreach ($results as $result) {
			$tmp = json_decode($result);
			
			//If there are any vehicles on the route
			if (count($tmp->response) > 0) {
				$vehicle_list = $tmp->response->entity;
				foreach ($vehicle_list as $vehicle_info) {
					$lat = $vehicle_info->vehicle->position->latitude;
					$long = $vehicle_info->vehicle->position->longitude;
					//$bearing = $vehicle_info->vehicle->position->bearing;
					$timestamp = $vehicle_info->vehicle->timestamp;
					
					$vehicle = array('latitude' => $lat, 'longitude' => $long, 
									 'timestamp' => $timestamp, 'route_name' => $route_name);
					
					array_push($vehicles, $vehicle);
			
				}
			}
	
		}
		
		echo json_encode($vehicles); //return json encoded vehicles array to index.php
		
	}
	
	//sanitizeInput: 
	function sanitizeInput($conn, $input) {
	    if (get_magic_quotes_gpc())
	    {
	        $input = stripslashes($input);
	    }
	    $input = $conn->real_escape_string($input);
	    return htmlentities($input);
	}
?>
