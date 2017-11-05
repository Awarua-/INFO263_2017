var map;
var markers = [];
var markerDetails = {};

//initMap: Initializes map and calls setMarkers to add markers to the map
function initMap() {
    var uluru = {lat: -36.848461, lng: 174.763336};
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 11,
      center: uluru
    });
}
  
  
//setMarkers: calls resetMap and then adds all passed in location as markers on the map
//			  Also adds all new markers to an empty markers array
function setMarkers(locations) {
	resetMap();
	if(Object.keys(locations).length > 0) {
		for (var i = 0; i < Object.keys(locations).length; i++) {
			var location = locations[i]; //get ith array in array
			var pos = new google.maps.LatLng(location['latitude'], location['longitude']);
			var marker = new google.maps.Marker({
				position: pos,
				map: map, 
				clickable: true
			});
			
			//set marker info window information
			marker.info = new google.maps.InfoWindow({
  			content: getInfoString(location)
  			});
			
			markers.push(marker);
			//set click event for marker
			google.maps.event.addListener(marker, 'click', function() {
				this.info.open(map, this);
				
    		});
	    }
	 	setBounds();
	}
	else { //no bus is running reset map
		initMap();

	}
}


//resetMap: loops through all markers in the markers array and removes them from the map
//			The markers array is then set to empty
function resetMap() {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers = [];
}

//setBounds: resizes the map to show all markers
function setBounds() {

    var bounds = new google.maps.LatLngBounds();
    for (var i=0; i < markers.length; i++) {
        bounds.extend(markers[i].getPosition());
    }
    
    if(markers.length == 1) {
    	map.fitBounds(bounds);
		map.setZoom(16);
    }
    else {
    	map.fitBounds(bounds);
    }
}

//getInfoString: returns the info window string for a certain marker
function getInfoString(location) {
	var time = new Date(location['timestamp'] * 1000);
	var hours = time.getHours();
	var mins = time.getMinutes();
	var secs = time.getSeconds();
	var suffix = ' am';
	
	if(secs < 10) {
		secs = '0' + secs;
	}
	
	if(mins < 10) {
		mins = '0' + mins;
	}
	
	if (hours >= 13) {
		hours = hours % 12;
		suffix = ' pm';
	}
	
	var infoString = 'Latitude: ' + location['latitude'].toString() + '<br>' + 
					 'Longitude : ' + location['longitude'].toString() + '<br>' +
				     'Route: ' + location['route_name'] + '<br>' +
					 'Time: ' + hours + ':' + mins + ':' + secs + suffix;
	
	return infoString;
}











