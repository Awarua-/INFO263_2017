<?php
$hostname = "localhost";
$database = "akl_transport";
$username = "root";
$password = "";


// Create connection
$connect = mysqli_connect($hostname, $username, $password);
mysqli_select_db($connect, $database);


$query = "SELECT DISTINCT * from stops";



 // $sql = "SELECT stop_id, stop_lat, stop_long FROM stops";
$result = mysqli_query($connect, $query);

while($row = mysqli_fetch_array($result))
  {

    echo "$row[0] - $row[1] - $row[2] - $row[3]<br>";

  }

  mysqli_free_result($result);
  
    mysqli_close($connect);
?>