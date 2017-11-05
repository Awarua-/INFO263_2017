<?php

    // MQSQLI object to query akl_transport for distinct route_short_name

    function get_routes()
    {
        include 'config.php';
        $conn = new mysqli($hostname, $username, $password, $database);
        if ($conn->connect_error)
            die($conn->connect_error);
        $query = "SELECT DISTINCT route_short_name FROM routes";
        $result = $conn->query($query);
        if (!$result)
            die($conn->error);
        $rows = $result->num_rows;
        $routes = [];
        for ($j = 0 ; $j < $rows ; ++$j) {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            array_push($routes,$row['route_short_name']);
        }
        $result->close();
        $conn->close();
        return $routes;
    }

    // Query akl_transport database for each route_id based on route_short_name searched by the user

    function get_route_ids($route_short_name)
    {
        include 'config.php';
        $conn = new mysqli($hostname, $username, $password, $database);
        if ($conn->connect_error)
            die($conn->connect_error);
        $query = "SELECT route_id FROM routes WHERE route_short_name = '$route_short_name'";
        $result = $conn->query($query);
        if (!$result)
            die($conn->error);
        $rows = $result->num_rows;
        $route_ids = [];
        for ($j = 0 ; $j < $rows ; ++$j) {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            array_push($route_ids,$row['route_id']);
        }
        $result->close();
        $conn->close();
        return $route_ids;
    }

    // Query akl_database to select relevant trip_ids for the route_short_name searched by the user
    function get_trip_ids($route_ids)
    {
        include 'config.php';
        $conn = new mysqli($hostname, $username, $password, $database);
        if ($conn->connect_error)
            die($conn->connect_error);
        $trip_ids = [];
        foreach ($route_ids as $route_id)
        {
            $query = "SELECT trip_id FROM trips WHERE route_id = '$route_id'";
            $result = $conn->query($query);
            if (!$result)
                die($conn->error);
            $rows = $result->num_rows;
            for ($j = 0 ; $j < $rows ; ++$j) {
                $result->data_seek($j);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                array_push($trip_ids, $row['trip_id']);
            }
            $result->close();
        }
        $conn->close();
        return $trip_ids;
    }

    // JSON parser to format vehicle location data

    function vehicle_json_parser($json)
    {
        $vehicles = [];
        $vehicle_objects = [];
        $response = $json['response'];
        if (sizeof($response) > 0) {
            foreach ($response['entity'] as $vehicle) {
                array_push($vehicles, $vehicle);
            }
            for ($i = 0; $i < count($vehicles); ++$i) {
                /*            $vehicle_objects[$i] = new Vehicle;
                            $vehicle_objects[$i]->vehicle_id = $vehicles[$i]['vehicle']['vehicle']['id'];
                            $vehicle_objects[$i]->latitude = $vehicles[$i]['vehicle']['position']['latitude'];
                            $vehicle_objects[$i]->longitude = $vehicles[$i]['vehicle']['position']['longitude'];
                            $vehicle_objects[$i]->start_time = $vehicles[$i]['vehicle']['trip']['start_time'];
                            $vehicle_objects[$i]->timestamp = $vehicles[$i]['vehicle']['timestamp'];*/
                $id_string = $vehicles[$i]['vehicle']['vehicle']['id'];
                $lat_string = $vehicles[$i]['vehicle']['position']['latitude'];
                $lng_string = $vehicles[$i]['vehicle']['position']['longitude'];
                $start_time = $vehicles[$i]['vehicle']['trip']['start_time'];
                $timestamp_string = $vehicles[$i]['vehicle']['timestamp'];
                $vehicle_objects[$i] = [];
                array_push($vehicle_objects[$i], $id_string);
                array_push($vehicle_objects[$i], $lat_string);
                array_push($vehicle_objects[$i], $lng_string);
                array_push($vehicle_objects[$i], $start_time);
                array_push($vehicle_objects[$i], $timestamp_string);
            }
        }
        return $vehicle_objects;
    }
?>