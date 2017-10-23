<?php
$active = "home";
require_once 'include/header.php';
require_once 'include/config.php';
require_once 'requests.php';
require_once 'database.php';
?>
<div id="map"></div>
<div id="databaseTest">
<script src='scripts/wkx.js'></script>
<script src='scripts/map.js'></script>
<?php
	//Initialise variables
	$jsonCoordinates = NULL;
	$jsonShapedata = NULL;
	$trip_ids = NULL;
	
	
	$array = getAllRoutes($conn);
	$current_route = '';
	
	//Begin HTML for routes table
	echo "<table class='table table-striped table-bordered table-hover table-condensed'>";
	echo "<thead><tr><th>Route Number</th><th>Description</th><th>Select</th><tr></thead><tbody>";

	foreach($array as $row){
		//Loop through routes, adding each new route short name to the table
		if ($current_route != $row[1]) {
			//Add a row to the table
			$current_route = $row[1];
			echo "<tr><td>$row[1]</td><td>$row[2]</td>";
			echo "<form method='post'>";
			echo "<input type='hidden' name = 'route_short_name' id = 'route_short_name' value = '$row[1]'/>";
			echo "<input type='hidden' name = 'route_id' id = 'route_id' value = '$row[0]'/>";
			echo "<td><input name='viewroute' type='submit' id='viewroute' value='View Route'/>";
			echo "</form></tr>";			
		}
	}
	echo "</tbody></table>";
	
	if(isset($_POST['viewroute'])){
		$trip_ids = getTrips($conn, $_POST['route_short_name'], $trips_statement);
		$coordinates = array();
		if (count($trip_ids) > 0) {
			//At least one trip associated with the route. Get bus details for the trip/s 
			$coordinates = getTripInfo($APIKey, $trip_ids);
		}
		$shapedata = getShapeData($conn, $APIKey, $_POST['route_id'], $shape_statement);
		$GLOBALS['jsonCoordinates'] = json_encode($coordinates);
		$jsonShapedata = json_encode($shapedata);
	}
	
?>
</div>
<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuDGxvJsHi8CZfns8q4TCExP052T_9rD8&callback=initMap"> // GOOGLE MAPS API
</script>

<script>
    $(document).ready(function() {
    });
</script>

<script type="text/javascript">
	var phpValue = <?= json_encode(getTripInfo($APIKey, $trip_ids))?>;
	var geoData = parseShapeData( <?= $jsonShapedata?>);
	
	updateMap(phpValue, geoData);
	
	function update(){
		var phpValue = <?= json_encode(getTripInfo($APIKey, $trip_ids))?>;
		var geoData = parseShapeData( <?= $jsonShapedata?>);
	}
	update();
	setInterval(update, 30000);
	
</script>

<?php
require_once 'include/footer.php';
?>
		