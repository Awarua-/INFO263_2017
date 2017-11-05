<?php
// This is the database.php file for INFO263 by GroupDev N
require_once 'include/config.php';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

//Function that produces an error message if there is indeed an error
function fatalError($error)
{
    $message = mysql_error();
    echo <<< _END
Something went wrong :/

<p>$error: $message</p>
_END;
}

//function to get all routes via short name and populate the dropdown menu
function populateRoutes($conn)
{
	$fetch_query = $conn->query("SELECT distinct routes.route_short_name FROM akl_transport.routes ORDER BY route_short_name ASC");

	while ($row = $fetch_query->fetch_assoc()) {
		echo '<option value="'.$row['route_short_name'].'">'.$row['route_short_name'].'</option>';
	}
	$fetch_query->close();
}

//function to get all trip ids for a paricular route and return them as an array
function getTripIds($conn, $param)
{
	#SQL statement to get trip ID
	$results = array();
    $get_trip_query = "SELECT DISTINCT trip_id FROM trips JOIN routes on routes.route_id = trips.route_id WHERE routes.route_short_name = '".$param."'";
    $result = $conn->query($get_trip_query);
    while ($row = $result ->fetch_array((MYSQLI_ASSOC))) {
		//$results[] = $row['trip_id'];
		array_push($results, $row['trip_id']);
	}
    $result->close();
    
	//echo var_dump($results);
    return $results;
}

?>