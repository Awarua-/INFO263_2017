<?php
$active = "home";
require_once 'include/header.php';
?>

<!--Create a Dropdown list-->
<select id ="dropDown">
	<option value= 'NULL' > Select Route </option>
	
	<?php // query.php
		//Import configurations about DATABASE
		require_once 'include/config.php';
		require_once 'get_routes.php';
		$conn = new mysqli($hostname, $username, $password, $database);

		get_routes($conn);
	 ?>
</select>

<!--DROP DOWN SELECTION EVENT-->
<script src="scripts/extract_vehicle.js"></script>

<script src="scripts/route_select_event.js"></script>


<!--GOOGLE MAP-->
<div id="map"></div>

<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWVvlBxK3Hm7BqW97c4cXFKY5wTcpG2vc&callback=initMap">
</script>

<script src="scripts/map.js"></script>


<!--MESSAGE STATUS-->
<p id="vehicle-msg"></p>


<!--MAP REFRESH AND UPDATE-->
<script src="scripts/map_refresh.js"></script>


<?php
require_once 'include/footer.php';
?>
