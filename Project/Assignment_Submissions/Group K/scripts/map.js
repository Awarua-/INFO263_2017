// Step 0: Make the map a global variable!
var map;
// here we are creating an array for the markers on the map in order to control markers belong to each selected route
// this way we can empty this array when finding vehicles for a new selection, to avoid showing old vehicles
var markers = [];

// here we are setting up the map
function initMap() {
	// Step 1
	var auck = {lat: -36.848461, lng: 174.763336};
	// Step 2: Set up the Map (for all markers)
	map = new google.maps.Map(document.getElementById('map'), {zoom: 14,center:auck});
}
