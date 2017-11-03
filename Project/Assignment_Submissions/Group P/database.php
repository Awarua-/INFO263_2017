<?php
require_once("config.php");

// Create connection
$connect = mysqli_connect($hostname, $username, $password);
mysqli_select_db($connect, $database);
$query = "SELECT DISTINCT route_short_name from routes";
// $sql = "SELECT stop_id, stop_lat, stop_long FROM stops";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
    echo $result->fetch_assoc()['route_short_name'] . '<br>'; //"$row[0]<br>";
}


mysqli_free_result($result);

mysqli_close($connect);
?>
