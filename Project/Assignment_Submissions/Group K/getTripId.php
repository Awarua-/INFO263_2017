<?php
// here we are including the important information for accessing the database, and the API key
require_once "include/config.php";
// here we are creating some global variables which will be used in the functions below
$count_limit = 0;
$results = "";

// This function is to protect against malicious injections
function sanitizeInput($input, $conn) {
	// here we are checking for quote marks, injected to attack or break SQL code
	if (get_magic_quotes_gpc()) {
		// if there are, then we will perform stripslashes to counteract this
		$input = stripslashes($input);
	}
	// then we will use the real_escape_string method relating to the connection object
	$input = $conn->real_escape_string($input);
	// then finally we wil use the html entities function when returning the input so 
	// that any injected input to attack or break our HTML code is stopped and controlled
	return htmlentities($input);
}

// Define query trips function where I will get the selection and query the database, then retrieve results!
function queryTrips($selection, $hostname, $username, $password, $database, $APIKey) {
	
	// here we are creating a new connection variable, with the key information, to connect to the database
	$conn = new mysqli($hostname, $username, $password, $database);
	// here we are doing an error check
	if ($conn->connect_error) {
		die($conn->connect_error);
	}
	// here we are taking the selection received from the AJAX post and sanitizing it
	$selection = sanitizeInput($selection, $conn);
	// here we are creating a prepared statement query, as a way to protect and safeguard against injections and malicious users
	$stmt = $conn->prepare("SELECT * FROM routes JOIN trips ON routes.route_id = trips.route_id WHERE route_short_name = ?");
	// route_short_name is a VARCHAR on the database which is a string
	$stmt->bind_param('s', $selection);
	// then we are executing our query to the database
	$stmt->execute();
	// an error check incase there is a problem with this query
	if ($stmt->errno) {
		fatalError($stmt->error);
	}
	// now we are collecting the results from our query from the database
	$stmt_result = $stmt->get_result();			
	// now we are checking that there are actually some results, if not we are erroring out
	if (!$stmt_result) {
		die($conn->error);
	}	
	// here we will be going through results, and using a loop to save each result into an array			
	$stmt_result_rows = $stmt_result->num_rows;	
	for ($j = 0; $j < $stmt_result_rows; ++$j) {
		$stmt_result->data_seek($j);
		$row = $stmt_result->fetch_array(MYSQLI_ASSOC);
		// here we are appending each trip id that is found from the query, to a trip ids arra
		$trip_ids [] = $row['trip_id'];
	}	
	// then to avoid running out of memory and wasting computer capacity we are closing the results and the connection object
	$stmt_result->close();
	$conn->close();		
		
	// API key is in config (already included to this page - up the top)
	$url = "https://api.at.govt.nz/v2/public/realtime/vehiclelocations";	
	// here we are converting our numerical array with trip ids into an associative array for the trip ids
	$params = array("tripid" => $trip_ids);
	// now we are including, but compulsorily (requiring), only once, the requests file which contains the function
	// that we will be calling below - in order to interact with the auckland transport website and collect JSON results
	require_once "requests.php";
	
	// here we are calling the apiCall function (defined in the requests file) and storing it in the results variable
	$results = apiCall($APIKey, $url, $params);		
	// Tell the browser we are sending back json
	header('Content-Type: application/json');
	
	// here we are finding the number of results to know whether there were any results or not
	$count_limit = count($results);
	// here we are checking whether there were any results or not
	if ($count_limit === 0) {
		// if there were not then an error message will display
		echo "No vehicles on this route!";
	} else {
		// if there are some results then we will perform the code below
		// here we will be taking the relevant data from the JSON objects and then creating 
		// our own JSON objects, with just the information we will be using for creating the markers on the google map
		// here numerical array for associative arrays of vehicles
		$json_vehicles = [];
		// associative array for vehicles
		$vehicles = [];
		
		// convert JSON into PHP object
		for ($count = 0; $count < $count_limit; $count++) {
			$vehicleJSON = $results[$count];
			// Convert JSON string to Object
			$vehicleObject = json_decode($vehicleJSON);
			// check if response is empty
			
			if (empty($vehicleObject->response)) {
				//echo $count." has no response! \n";
			} else {
				$counter_limit = count($vehicleObject->response->entity);
				for ($b = 0; $b < $counter_limit; $b++) {
					// store id, latitude & longitude into associative array
					$vehicles = ["id"=>$vehicleObject->response->entity[$b]->vehicle->vehicle->id,"latitude"=>$vehicleObject->response->entity[$b]->vehicle->position->latitude, "longitude"=>$vehicleObject->response->entity[$b]->vehicle->position->longitude, "trip_id"=>$vehicleObject->response->entity[$b]->vehicle->trip->trip_id, "route_id"=>$vehicleObject->response->entity[$b]->vehicle->trip->route_id, "timestamp"=>$vehicleObject->response->entity[$b]->vehicle->timestamp];
					// append associative array to numerical array
					$json_vehicles[] = $vehicles;
				}	
			}
		}
		// then code up into JSON object
		$vehicle_info = json_encode($json_vehicles);
		// here we will store our own JSON object we made on the network and then javascript can recognise this later
		echo $vehicle_info;
	}
}

// Here is the actual routine of this file

// here we are checking that there is a route that was selected
if (!isset($_POST['route_short_name'])) {
	// if there isn't then we will error out
	echo "Error!!!";
} else {
	// if there is a selected route then we are storing this as a PHP (basic) variable
	$selected_route = $_POST['route_short_name'];
	// then we are calling the queryTrips function and using the config file 
	// and the variable above as the parameters to be used in this function 
	queryTrips($selected_route, $hostname, $username, $password, $database, $APIKey);
}
?>

