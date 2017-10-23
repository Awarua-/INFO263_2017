//Checks the document if it is ready
//Calls the function update_map every 30 secs
$(document).ready(function() {
	setInterval(function() {
	update_map()
}, 30000)
});


function update_map() {
	//Grabs the last route selected
	//Assuming there are still markers on the map
	choosen_route = last_route_selected;
	if (choosen_route !== null && choosen_route !== "NULL") {
		//Make sure it is not NULL or the initial value 'SELECT ROUTE'
		$.get( "get_vehicle.php", { choosen_route: choosen_route }, function( vehicle_positions_raw ) {
			var vehicle_positions = json_extractor(vehicle_positions_raw);
			setMarkers(vehicle_positions)
			
			//If there is no vehicles returned, then modifies an element to notify the user
			if (vehicle_positions.length == 0) {
	  			document.getElementById("vehicle-msg").innerHTML = "NO VEHICLES FOUND <i class=\"icon-frown icon-2x\" aria-hidden=\"true\"></i>";
	  			initMap();
	  		} else {
	  			document.getElementById("vehicle-msg").innerHTML = "";
			}
		});
	}
}