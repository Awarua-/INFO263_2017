<?php
	function get_routes($conn)
	{
		//Checks the connection
		if ($conn->connect_error) die($conn->connect_error);
		
		//Prepare a query and stores the result in a variable
		$query = $conn->prepare("SELECT DISTINCT route_short_name FROM routes ORDER BY route_short_name");
		$query->execute();
		$result = $query->get_result();
		
		
		if (!$result) die($conn->error);
		$rows = $result->num_rows;
		
		//Extracts route short name and echoes out to be HTML
		for ($j = 0 ; $j < $rows ; ++$j){
			$result->data_seek($j);
			$res = $result->fetch_assoc()['route_short_name'];
			echo "<option value= '$res' > $res </option>";
		}
		$result->close();
		$conn->close();
	}
 ?>
