<?php
$active = "home";
require_once 'include/header.php';
?>
<div id="map"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBJLCWIWft6synfEqhIs_dJhltOsmS90oI"></script>
	<script src="scripts/map.js"></script>
	<img src="bus.png" width="80">

	<select id="route-select">
		<option id="loading" value="loading">Loading....</option>
	</select>
<script src="scripts/updateRouteList.js"></script>
<?php
require_once 'include/footer.php';
?>
<!-- API key = AIzaSyBJLCWIWft6synfEqhIs_dJhltOsmS90oI --> 