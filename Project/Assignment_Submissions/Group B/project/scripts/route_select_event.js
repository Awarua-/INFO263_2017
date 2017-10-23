//Create a global variable to be accessed within the domain
var last_route_selected;

$(document).ready(function(){
	//User selects a route in the drop down list
	$("#dropDown").change(function(){
		var choosen_route = $(this).val();
		//Make sure it is not NULL or the initial value 'SELECT ROUTE'
		if (choosen_route !== null && choosen_route !== "NULL") {
			//Modifies an element to show it is currently loading
			document.getElementById("vehicle-msg").innerHTML = "<img src=\"loading.gif\" alt=\"LOADING...\" style=\"width:150px;height:100px;\">";
			last_route_selected = choosen_route
			
			//AJAX call to get vehicles from AUCKLAND API
			$.get( "get_vehicle.php", { choosen_route: choosen_route }, function( vehicle_positions_raw ) {
				//Extracts the useful information from the vehicle
  				var vehicle_positions = json_extractor(vehicle_positions_raw);
  				//Places markers on the map
				setMarkers(vehicle_positions)
				
				//If there is no vehicles returned, then modifies an element to notify the user
				if (vehicle_positions.length == 0) {
  					document.getElementById("vehicle-msg").innerHTML = "NO VEHICLES FOUND <i class=\"icon-frown icon-2x\" aria-hidden=\"true\"></i>";
  					last_route_selected = "NULL";
  					initMap();
  				} else {
  					document.getElementById("vehicle-msg").innerHTML = "";
				}
			});
		}
	});
});
