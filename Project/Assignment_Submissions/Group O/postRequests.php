<?php
	require_once("include/config.php");
	require_once("requests.php");
	$conn = getConnection($hostname, $username, $password, $database);
	if ($conn->connect_error) {
		fatalError($conn->error);
		return;
	}

	//If the POST variable is set to get all the routes
	if (isset($_POST['allRoutes'])) {
		$query = 'SELECT DISTINCT route_short_name FROM akl_transport.routes ORDER BY route_short_name ASC';
		$result = $conn->query($query);
		$routeArray = getAllRoutes($result);
		$conn->close();

		echo($routeArray);
	}
	//If the POST variable is set to query the API
	else if (isset($_POST['queryAPI'])) {
		$param = $_POST['route'];
		$param = $conn->real_escape_string($param);
		$query = $conn->prepare('SELECT DISTINCT trip_id FROM trips, routes WHERE routes.route_id = trips.route_id AND routes.route_short_name = ?');
		$query->bind_param('s', $param);
		$query->execute();
		$result = $query->get_result();
		$trips = getTripIds($result);
		$conn->close();

		$trip_array = array("tripid" => $trips);
		$apiJSON = apiCall($APIKey, $url, $trip_array);
		$busArray = processJSON($apiJSON);
		$busJSON = json_encode($busArray);
		header('Content-Type: application/json');
		echo($busJSON);
	}


  /**
	*	Establishes a connection with the database. If it is unable then an error occurs.
	*
	*	@param string $hostname		hostname of the database
	*	@param string $username		username required for database
	*	@param string $password		password required for database
	* @param string $database		name of the database
	* @return object				Connection object is returned
	*/
	function getConnection ($hostname, $username, $password, $database) {
		$conn = new mysqli($hostname, $username, $password, $database);

		if ($conn->connect_error) {
			fatalError($conn->connect_error);
			return false;
		} else {
			return $conn;
		}
	}


  /**
	*	Performs a query on the database to get all of the routes.
	*
	*	@param array $queryResult	The resulting array from the route query
	*	@return	json				a json encoded string is returned of all routes
	*/
	function getAllRoutes($queryResult) {
		$routes = array();
		if ($queryResult) {
			while ($row = $queryResult->fetch_array(MYSQLI_ASSOC)) {
		        $routes[] = $row['route_short_name'];
		    }
			$json = json_encode($routes);
		}
		return $json;
	}


	/**
	*	Performs a query on the AKL-transport database to get all the trip ids for a given route
	*
	*	@param array $queryResult	The resulting array from the tripid query
	*	@return array $trips		The array of all tripids which will be used to query AT API
	*/
	function getTripIds($queryResult) {
		$trips = array();
		while ($row = $queryResult->fetch_array(MYSQLI_ASSOC)) {
			$trips[] = $row['trip_id'];
	    }
		return $trips;
	}


	/**
	*	Proccesses the JSON which is returned by the AT API and returns an array of busses
	* 	which will be used to add markers onto a map. It contains extra information that isn't needed
	*
	* 	@param string $json		JSON encoded string which contains all information about busses
	* 	@return string $busses 	JSON encoded string which has bus id, latitude, longitude of each bus
	*/
	function processJSON($json) {
		$busses = array();
		$len = count($json);
		$test = '';
		//Loop through trips on a route
		for ($i = 0; $i < $len; $i++) {
			$data = json_decode($json[$i]);

			//Check to see if a bus is present on the trip
			if (is_object($data->response)) {
				$busData = $data->response->entity;
				//Loop through busses on the trip
				for ($j = 0; $j < count($busData); $j++) {
					$id = $busData[$j]->vehicle->vehicle->id;
					$lat = $busData[$j]->vehicle->position->latitude;
					$long = $busData[$j]->vehicle->position->longitude;
					$startTime = $busData[$j]->vehicle->trip->start_time;

					array_push($busses, array('id' => $id, 'lat' => $lat, 'long' => $long, 'time' => $startTime));
				}
			}
		}
		return($busses);
	}
?>
