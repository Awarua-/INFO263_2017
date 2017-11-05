<?php
require_once("include/config.php");


/**
* database connection to connect to the auckland API database 
*/
$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

/**
 * Echos an mysql error.
 *
 * @param string $error The error passed.
 */
function fatalError($error)
{
    $message = mysql_error();
    echo <<< _END
Something went wrong :/
<p>$error: $message</p>
_END;
}


/**
* debugging function only
* Prints the returned information from the database
*/
function printAllRoutes($array)
{
	foreach ($array as $item)
	{
		echo $item . "<br>";
	}
}

/**
* debugging function only
* Prints the returned information from the database
*/
function printAllAttributes($array)
{
	$i = 0;
	foreach ($array as $item)
	{
		if ($i % 5 == 0) {
			console.log("Route ID = " + $item);
			$i++;
		} elseif ($i % 5 == 1) {
			console.log("Route Short Name = " + $item);
			$i++;
		} elseif ($i % 5 == 2) {
			console.log("Route Long Name = " + $item);
			$i++;
		} elseif ($i % 5 == 3) {
			console.log("Route Type = " + $item);
			$i++;
		} elseif ($i % 5 == 4) {
			console.log("Agency ID = " + $item);
			$i++;
		}
	}
}

 
/**
 * Queries the database for all route IDs.
 *
 * @param mysqli $conn A connection to a mysql database.
 * @return a list of routes.
 */
function getAllRoutes($conn)
{
	$query = "SELECT route_id FROM akl_transport.routes;";
	$result = $conn->query($query);
	if (!$result) die($conn->error);
	$rows = $result->num_rows;
	for ($j = 0; $j < $rows; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		}
	$result->close();	
}


/**
 * Queries the database for all route information for a specific a route ID.
 * 
 * @param mysqli $conn A connection to a mysql database.
 * @param a specific route ID.
 * @return $routeArray list of routes.
 */
function getRouteInfo($conn, $routeID) {
	
	$query = "SELECT route_id, route_short_name, route_long_name, route_type, agency_id FROM akl_transport.routes WHERE route_id = '" .sanitizeInput($conn, $routeID). "' ORDER BY route_short_name;";
	$result = $conn->query($query);
	if (!$result) die($conn->error);
	$rows = $result->num_rows;
	$routeArray = array();
	for ($j = 0; $j < $rows; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);

        $route = $row['route_id'];
        $info = array();
        unset($row['route_id']); // remove the route_id as it is going to be the key
        $routeArray['route_id'] = $route;
        $routeArray['info'] = $row;
		}
	$result->close();

	return $routeArray;
}


/**
 * Sanitizes an input string.
 * Check to see if magic quotes are being used, if they are strip slashes from the input string.
 * Use the real_escape_string function of the database to escape the input string.
 * prevent XSS by calling htmlentities function on the input string.
 * Return the sanitized string.
 *
 * @param string $input The string to sanitize.
 * @param mysqli $conn A connection to a mysql database.
 * @return a sanitized string.
 */
function sanitizeInput($conn, $input)
{
	if (get_magic_quotes_gpc()) 
	{
		$input = stripslashes($input);
	}
	$input = $conn->real_escape_string($input);
	return htmlentities($input);
}

/**
 * This function takes a stop id, finds the name associated to it from the database and returns it.
 *
 * @param mysqli $conn      A connection to a mysql database
 * @param string $stopID    The stop id
 * @return array $row       The name of the next bus stop
 */
function getStopName($conn, $stopID) {
    $query = "SELECT stop_name FROM akl_transport.stops WHERE stop_id = '" . sanitizeInput($conn, $stopID) . "';";
    $result = $conn->query($query);
    if (!$result) die($conn->error);
    
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $result->close();
    $conn->close();
    return $row;
}

?>