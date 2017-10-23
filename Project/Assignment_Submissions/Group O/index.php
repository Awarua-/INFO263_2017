<?php
$active = "home";
require_once('include/header.php');
require_once("include/config.php");
?>
<html>
	<head>
		<script async defer
		  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0Ln7RlpdUdfz7ZBc331XtKX3XOa4KVW8&callback=initMap">
		</script>
		<script src="scripts/map.js"></script>
		<script src="scripts/scripts.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>


		<div id="navbar">
		<table>
		<tr>
		<td>
			<div id="ltab"><b>Lookup route:</b></div>
		</td>
		<td>
			<select id="routeSelect">
				<option value="None">Please select a route</option>
			</select>
		</td>
		</tr>
		</table>

		<table id="checkboxtable">
		<tr>
		<td>
		<p>auto-center:</p>
		</td>
		<td>
			<label class="switch">
			  <input id="autosizecb" type="checkbox" checked>
			  <span class="slider round"></span>
			</label>
		</td>
		</tr>
		</table>
		</div>

		<div id="map"></div><br>
		<div id="output">
			<p id="routeOutput">
				Please select a route.
			</p>
		</div>
		<script>
		    $(document).ready(function() {
					routeQuery();

					$('#routeSelect').on('change', function() {
						APIquery();
					})

				});
		</script>
	</body>
</html>
<?php
require_once 'include/footer.php';
?>
