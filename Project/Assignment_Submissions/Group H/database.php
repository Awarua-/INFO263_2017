<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
require_once("include/config.php");

$conn = new mysqli($hostname, $username, $password, $database);

//Prepare the statements called with each new route displayed
$shape_statement = $conn->prepare("SELECT shape_id FROM trips WHERE route_id = ?");
$trips_statement = $conn->prepare("SELECT * FROM trips JOIN routes ON trips.route_id = routes.route_id WHERE trips.route_id IN (SELECT route_id FROM routes WHERE route_short_name = ?);");
    
if ($conn->connect_error)
{
	echo($conn->connect_error);
    fatalError($conn->connect_error);
    return;
}

/**
 * Queries the database for all route ids.
 * *
 * @param mysqli $conn A connection to a mysql database.
 *
 * @return a list of route ids
 */
function getAllRoutes($conn)
{
    $results = array();
    $query = "SELECT route_id, route_short_name, route_long_name FROM routes ORDER BY route_short_name;";
    $result = $conn->query($query);
    if (!$result)
    {
        fatalError($conn->error);
    }
    else {
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $results[] = [$row['route_id'], $row['route_short_name'], $row['route_long_name']];
        }

        $result->close(); 
    }
    return $results;
}

function getShapeData($conn, $APIKey, $route_id, $shape_statement) {
    $sanitised_input = $conn->real_escape_string($route_id);
    $shape_statement->bind_param('s', $sanitised_input);
    $shape_statement->execute();
    $shape_result = $shape_statement->get_result();
    $shape_data = "";
    $row = $shape_result->fetch_assoc();
    $shape_data = apiCall($APIKey, 'https://api.at.govt.nz/v2/gtfs/shapes/geometry/' .  $row["shape_id"], NULL);
    return $shape_data;
}

function getTrips($conn, $route_short_name, $trips_statement) {
	$sanitised_input = $conn->real_escape_string($route_short_name);
    $trips_statement->bind_param('s', $sanitised_input);
    $trips_statement->execute();
    $results = array();
    $result = $trips_statement->get_result();
    if (!$result)
    {
        fatalError($conn->error);
    }
    else {
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $results[] = [$row['trip_id']];
        }
        $result->close();
    }
    return $results;
}


 /* Echos an mysql error.
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
 ?>