<?php //database.php
require_once 'include/config.php';

class Database
{
    private $hostname;
    private $username;
    private $password;
    private $database;
    
    /**
    * Creates an instance of ParallelGet.
    * @param string $hostname hostname of the database server
    * @param string $username username of the database server
    * @param string $password password of the database server
    * @param string $database database to use
    */
    function __construct($hostname, $username, $password, $database)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }
    
    function connectDatabase()
    {
        $conn = new mysqli(
        $this->hostname,
        $this->username,
        $this->password,
        $this->database
        );
        
        if ($conn->connect_error) {
            return "Connection failed: " . $conn->connect_error;
        }
        return $conn;
    }
    
    function getAll()
    {
        $conn=$this->connectDatabase();
        
        $sql = "SELECT * FROM routes";
        
        $result = $conn->query($sql);

        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            $myArray[] = $row;
        }

        $conn->close();

        return json_encode($myArray);

    }
    
    function getTripsByRouteId($routeId)
    {
        $conn=$this->connectDatabase();
        
        $sql = "SELECT * FROM trips where route_id='$routeId'";
        
        $result = $conn->query($sql);

        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            $myArray[] = $row;
        }

        $conn->close();
        
        return json_encode($myArray);
    }
}
