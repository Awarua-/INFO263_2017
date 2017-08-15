<?php //query.php
require_once 'include/common.php';
require_once 'include/config.php';

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

if (isset($_POST['query']))
{
    $table = sanitizeString($_POST['query']);
    switch($table)
    {
        case "stops":
            $table = "stops";
            break;
        case "routes":
            $table = "routes";
            break;
        case "vehicles":
            $table = "vehicles";
            break;
        default:
            $table = NULL;
    }
    if (!is_null($table))
    {
        $query = "SELECT distinct route_short_name FROM {$table} ORDER BY route_short_name;";
        $result = $conn->query($query);
        if (!$result)
        {
            echo $conn->error;
        }
        else {
            $results = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $results[] = $row;
            }

            echo json_encode($results);
        }
    }

}
?>
