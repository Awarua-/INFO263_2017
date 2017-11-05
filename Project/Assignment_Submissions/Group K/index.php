<?php
	// include database information (username, password etc ..) and API key for Auckland Transport's website
	require_once 'include/config.php';
	$active = "home";
	require_once 'include/header.php';
?>
	
<div id="select_route">
	<h3>Select Bus Route:</h3>
	<div class="dropdown">
		<select id="route_list_selection" onchange="getSelectedRoute()">
			<!-- Loop around these <select> tags filling it with the database's route types -->
			<?php	
			// create connection to database
			$conn = new mysqli($hostname, $username, $password, $database);
			// error check
			if ($conn->connect_error) {
				die($conn->connect_error);
			}
			// store SQL query as string 
			$route_list_query = "SELECT route_short_name, route_long_name FROM akl_transport.routes GROUP BY route_short_name, route_long_name;";

			// use connection method "query" to query the database using the SQL string above
			$route_list_result = $conn->query($route_list_query);
			// error check results from the query for short names
			if (!$route_list_result) {
				die($conn->error);
			}
			// find the number of rows of results to use in the for loop below - for short name
			$route_list_rows = $route_list_result->num_rows;

			// loop through query results and put each result into the dropdown list <option> tags
			for ($j = 0; $j < $route_list_rows; ++$j) {
				$route_list_result->data_seek($j);
				$row = $route_list_result->fetch_array(MYSQLI_ASSOC);
				?>
				<option value="<?php echo $row['route_short_name']; ?>">
					<?php 
					// for each result from this query we are displaying it, into these option tags
					// this will help form our dropdown list, as each result will become a separate option
					echo $row['route_short_name']." - ".$row['route_long_name']; 
					?>
				</option>
				<?php
			}	 
			// to be efficient and avoid running out of memory we now close the results
			// and the connection object since we have finished using them	
			$route_list_result->close();
			$conn->close();		
			?>
		</select><!-- Closing the select tag here to indicate the end of the dropdown list -->
		<div class="arrow"></div><!-- Closing the "Arrow" Tag -->
	</div><!-- Closing of the "Dropdown" Div Container (surronding the <select> tags) -->
</div><!-- End of Select Route Div -->

<div id="message">
	<!-- This div container is to be used to display feedback text to the user
		 for whether their selection has any vehicles to display or not (error message) -->
</div><!-- End of "Message" Div -->

<!-- Here is the Google Maps API and the corresponding javascript scripts -->
<div id="map"></div>

<!-- Google Maps Javascript API: AIzaSyCc2pU_d1dppYeWpcF5R8vQaa9nT366Gqs -->
<script async 
	defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCc2pU_d1dppYeWpcF5R8vQaa9nT366Gqs&callback=initMap">
</script>	
<script src="scripts/map.js"></script>
<script>
	$(document).ready(function() {
	});
</script>
<!-- Here we are sourcing the script that will determine what dropdown option 
     has been selected and then trigger a function -->
<script src="scripts/findSelection.js" ></script>	

<?php 
	require_once 'include/footer.php'; 
?>
