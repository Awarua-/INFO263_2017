<?php
    require_once 'include/config.php';
    $db=new mysqli($hostname,$username,$password,$database);
    if(mysqli_connect_error())
    {
        echo 'Could not connect to database.';
        exit;
    }
    $result=$db->query("SELECT DISTINCT(route_short_name) FROM routes");
    $row = $result->fetch_all();
    for($i = 0 ; $i < count($row); $i++)
    {
        $id = $row[$i][0];
        $tmp = $db->query("Select route_long_name from routes where route_short_name = '$id'")->fetch_all();
        $route_long_name = "";
        for($j = 0 ; $j < count($tmp); $j++)
        {
            $route_long_name = $route_long_name.$tmp[$j][0]." / ";
        }
        $data[$i]["route_long_name"] = $route_long_name;
        $data[$i]["route_short_name"] = $row[$i][0];
    }
    header('Content-type:text/json;charset=utf-8');
    echo json_encode($data);
