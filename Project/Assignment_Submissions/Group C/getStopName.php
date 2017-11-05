<?php
require_once "routes.php";

// get stop ids from the database
if (isset($_GET['stop_id'])) {
    $stop_id = $_GET['stop_id'];
    $stopName = getStopName($conn, $stop_id);

    header('Content-Type: application/json');
    print(json_encode($stopName));
}

?>