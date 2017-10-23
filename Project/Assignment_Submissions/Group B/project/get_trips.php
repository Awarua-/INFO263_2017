<?php
function get_trips($conn, $route_short_name)
{
	//Prepare a query and to avoid SQL injection
	$query = $conn->prepare("SELECT trip_id FROM trips WHERE route_id in (SELECT route_id FROM routes WHERE route_short_name = ?)");
	$query->bind_param('s', $route_short_name);
	$query->execute();
	$result = $query->get_result();
	
	$rows = $result->num_rows;
	//Create an array
	$trip_ids = array();
	
	//Append trip_id to the array
	for ($j = 0 ; $j < $rows ; ++$j){
		$result->data_seek($j);
		$res = $result->fetch_assoc()['trip_id'];
		array_push($trip_ids, $res);
	}
	
	//Close connection and return trip_ids
	$result->close();
	return $trip_ids;
}

?>