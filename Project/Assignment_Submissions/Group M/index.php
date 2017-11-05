<?php
	$active = "home";
	require_once('include/header.php');
	require_once('route.php');
	require_once('db.php');
	
	$routes = getAllRoutes($conn); //Gets all avaliable routes 
?>

<!---- HTML SECTION ---->
<!---- Map ---->
<div id="map"></div>

<!---- Error Box ---->	
<div class="error" style="display: none">Error: &nbsp;  No Bus Running</div>

<!---- Select Box ---->
<select id="routeList" size="40"> </select>
	
<!---- right column ---->
<div id="right-border"></div>
	
	
<!---- JAVASCRIPT SECTION ---->		
<script src="scripts/map.js"></script>

<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAEXq-mILqhBvy350qmUXloh1iVDftChsE&callback=initMap">
  $(document).ready(function() {});
</script>

<script>
	var select = document.getElementById("routeList");
	var routes = <?php echo json_encode($routes); ?>;
	routes.forEach(appendRoute);
	
	
	//appendRoute: iterates through route list and appends each route to the select box
	function appendRoute(route) {
		var option = document.createElement("option");
		option.value = option.text = route;
		
		select.add(option);
	}
	
	//displayErrorBox: displays an error box for a couple of seconds in the center of 
	//				   the screen telling the user that there are no buses running 
	// 				   for the selected route
	function displayErrorBox() {
		var div = document.getElementsByClassName("error");
		div[0].style.display = 'block'; //makes the error box visible
			setTimeout(function(){ //wait for 2.25 seconds and then 
				div[0].style.display = 'none'; //make the error box visible
			}, 2250);
		return;
	}
	
	
	//When a value has been clicked the element chosen is saved as chosenOption
	var chosenOption;
	$('#routeList').change(function() {
		chosenOption = $(this).val();
		queryDB();
	});
	
	
	//queryDB: calls the dp.php file which queries the API and returns the data
	// 			 the data is then sent to the convertData function
	function queryDB() {
		$.post("db.php", {route: chosenOption}, function(data) { convertData(data); }  );
	}
	
	
	//refresh_map: waits 30 seconds and then calls the query to update markers
	function refresh_map(option) {
		setTimeout(function(){ //wait for 5 seconds and then
			if(option == chosenOption) { //checks to see if option has changed while in timer phase
				queryDB();
			}
		}, 27000); //taking into account that the fetch time from the Auckland database is about 3 seconds
	}

	
	//convertData: If input data is blank (contains no buses) then an error box is displayed 
	//				 for a couple of seconds. If the data isn't empty it passes the information to set markers.
	//				 For either option refresh map is called to update every 30 seconds 
	//				 takes in the data from the Auckand API call 
	function convertData(data) {				
		var vehicles = data;
		if(typeof vehicles == typeof "string") { //check to see if db qeury returns null (no buses running)
			displayErrorBox();
			initMap();
			refresh_map(chosenOption);
		}
		else {
			if(vehicles.length == 0) { //check to see if array returned is empty (no buses running)
				displayErrorBox();				
				initMap();
				refresh_map(chosenOption);			
			}
			else {	//otherwise update markers and refresh map every 30 seconds
				setMarkers(vehicles);
				refresh_map(chosenOption);
			}
		}
	}	
	
	
</script>

<?php 
	require_once('include/footer.php');
?>