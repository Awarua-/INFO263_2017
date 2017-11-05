// define interval as a global variable - to be set later in getSelectedRoute() function
var interval;

// this function will receive all the relevant information from the JSON object that the client side receives
// this function is looped through for each result and for each vehicle the following proporties are created to 
// make a vehicle marker on the google map API for it
function addVehicle(id, latitude, longitude, trip_id, route_id, timestamp) {
	// Step 1: Set Co-ordinates of Vehicle
	var vehicle_location = {lat: latitude, lng: longitude};
	// Step 2: Add Custom Icon (to replace default red pointers)
	var icon = {
	    url: "https://image.flaticon.com/icons/png/128/164/164955.png", // url
	    scaledSize: new google.maps.Size(30, 30), // scaled size
	    origin: new google.maps.Point(0,0), // origin
	    anchor: new google.maps.Point(0, 0) // anchor
	};	
	
	// create title to display when user overs over a bus marker
	var pop_up = "Click for more information about Bus " + id;
	
	// Step 3: Set Marker on Map at Co-ordinates 
	var vehicle_marker = new google.maps.Marker({
		position: vehicle_location,
		map: map,
		icon: icon,
		title: pop_up
	});
	
	// Before Step 4: Convert Timestamp from Unix timestamp into Time!
	var date = new Date(timestamp*1000);
	var hours = date.getHours();
	var minutes = "0" + date.getMinutes();
	var seconds = "0" + date.getSeconds();
	var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
	var period = date.getHours() >= 12 ? 'pm' : 'am';
	
	
	// Step 4: Set additional information as variable
	var information = '<div id="iw_container">' + '<h2>Detailed Vehicle Information</h2>'
		+ '<p>' + 'Vehicle ID #: ' + id + '</br>' 
		+ 'Trip ID #: ' + trip_id + '</br>' 
		+ 'Route ID #: ' + route_id + '</br>'
		+ 'Time Stamp: ' + formattedTime + ' ' + period + '</br>'
		+ '</p>' + '</div>';
	// Step 5: Create pop up window with additional info in it
	var info_window = new google.maps.InfoWindow({
		content: information
	});
	// Step 6: Add function to the market so that the window with the additional info pops up when the market is clicked on
	vehicle_marker.addListener('click', function() {
		info_window.open(map, vehicle_marker);
	});
	// add this marker to the markers array for all vehicles on this route
	markers.push(vehicle_marker);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
	// loop through the markers array (current markers on the Google Map API)
	for (var x = 0; x < markers.length; x++) {
		// one by one, remove each marker off the Map
		markers[x].setMap(null);
	}
	// now empty the markers array as well
	markers = [];
}

// this is the function which is called once the selected route is determined, and this function is triggered every 30 seconds
// this function sends the selected route to the server (using jQuery AJAX post), then perform join query to get trip ids
// then perform apiCall to get vehicle informatio, then decode JSON, pick out info we want, reconstruct new JSON object
// send this to the client side, then use this to create each of the markers on the google map API
function peformQuery(selectedRoute) {	
	
	// using jquery to send chosen route to server and the getTripID file will query the server
	$.post("getTripId.php",
	{
		// send selection (received from javascript) to PHP page to use in query
		route_short_name: selectedRoute,
	},
	// receive data back (from console log) - this will be my custom made JSON object 
	// containing latitude & longitude of buses
	function(data, status){
		// clear feedback message to the previously selected route - to avoid confusion
		document.getElementById("message").innerHTML = "";
		
		// clear the markers currently on the map for the previously selected route
		deleteMarkers();
		// here we are finding the length of the data array result, and using this to limit the for loop
		var loop_stop = data.length;
		// here we are looping through the below code
		for (var i = 0; i < loop_stop; i++) {
			// for each item in the JSON array, we are calling this function to add a vehicle to it
			addVehicle(data[i].id, data[i].latitude, data[i].longitude, data[i].trip_id, data[i].route_id, data[i].timestamp);
		}	
				
		// call function to resize the map according the the new markers
		var bounds = new google.maps.LatLngBounds();
		// here we are looping through the markers array
		for (var y = 0; y < markers.length; y++) {
			// for each of the current markers (in the array) 
			// we are getting their position and saving it to the bounds
			bounds.extend(markers[y].getPosition());
		}
		// then once the loop is done and each current marker's postion is saved to the bounds
		// the map is instructed to fit the bounds of what we have established and saved just above
		map.fitBounds(bounds);
			
		// if there is no vehicles currently on the select route
		if (data.length !== 0) {		
			// now we are displaying a feedback message to the user, so they know they have been successful
			// and that there are indeed some vehicles on the route they selected
			$('#message').css('color', 'black');
			document.getElementById("message").innerHTML = "There are " + markers.length + " vehicles currently on the \"" + selectedRoute + "\" route!";	
		} else {
			// now we are displaying a feedback message to the user, so they know they have been unsuccessful
			// there are no vehicles belonging to the selected route they chose
			$('#message').css('color', 'red');
			document.getElementById("message").innerHTML = "Oops! There are 0 vehicles currently on the \"" + selectedRoute + "\" route!";
		}
	});
}

// using the "onchange" property of the dropdown list when a new selection is made
// this function will be triggered and execute the code inside it
function getSelectedRoute() {
	// check if an interval is already set
	if (typeof(interval) !== 'undefined') {
		// if an interval is already set then clear it
		clearInterval(interval);
	}
	// here we are finding what the selected option's value is, and storing it as a javascript variable
	var selectedRoute = document.getElementById("route_list_selection").value;
	// now we are calling the function below to perform the ajax post (jQuery), then return vehicle information and display it
	peformQuery(selectedRoute);
	// here we are reseting the interval to call the function each 30 seconds, to keep updating the 
	// vehicles postions as they move along the route
	interval = setInterval( function() { peformQuery(selectedRoute); }, 30000);
}	

// here I am calling this function originally, before it is triggered by a change in dropdown list selection
// this is in order to perform this function once the page is loaded, for the default selection "001" 
getSelectedRoute();
