<?php 
	require_once 'include/config.php';

	$conn = new mysqli($hostname, $username, $password, $database); //or csse-info263.canterbury.ac.nz
	if($conn->connect_error)
		{
			echo "<p><strong> Failed to connect to the database.</strong></p>";
			exit();
		}
	
	function getAllRoutes($conn) 
	{
		$query = "SELECT DISTINCT route_short_name FROM routes";
		$result = $conn->query($query);
		
		$routes = array();
		
		if(!$result) die($conn->error);
		$rows = $result->num_rows;
		
		for($j = 0; $j < $rows; ++$j) 
		{
			$result->data_seek($j);
			$row = $result->fetch_array(MYSQL_ASSOC);
			$value = $row['route_short_name'];
			array_push($routes, $value);
		}
		$result->close();
		return $routes;
	}
	
	function printRoutes($routes)
	{

		foreach ($routes as $route) 
		{
			echo $route;
		}

	}
?>