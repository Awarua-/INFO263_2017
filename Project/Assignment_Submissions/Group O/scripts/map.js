/**
* This file contains all the javascript that handles data on the map
*/

// Constants & global definitions
var allMarkers = [];
var allWindows = [];
var infoMap;
var initialPoint = {lat: -36.84738,lng: 174.76173};


// Initialises a new Google maps object called infoMap
function initMap(){
    infoMap = new google.maps.Map(document.getElementById('map'), {
      zoom: 11,
      center: initialPoint
    });
	setInterval(APIquery,30000);
}


//closes all inforation windows
function clearwindows(){
	for (var i = 0; i < allWindows.length; i++){
		allWindows[i].close();
	}
}


/**
*		Creates a new marker and places it on the map
*		@param {float} x - latidude of bus
*		@param {float} y - latidude of bus
*		@param {string} name - name of the bus
*		@param {string} route - route of the bus
*/
function newmarker(x,y,name,route) {
	locations.push([name,x,y,route]);
	var myLatLng = {lat: x,lng: y};

	//	create marker
	mark = new google.maps.Marker({
		position: myLatLng,
		map: infoMap,
		icon: "media/bus.png",
		title: 'Double click to zoom'
	});

	//	create information window
	var busInfo = notify(mark.getPosition());
	var infoWindow = new google.maps.InfoWindow({
		content: busInfo
	});

	//	keep track of markers and windows
	allMarkers.push(mark);
	allWindows.push(infoWindow);

	//	add click and double click events
	mark.addListener('click', function() {
		clearwindows();
		infoWindow.open(infoMap, this);
	});
	mark.addListener('dblclick', function() {
		infoMap.setZoom(15);
		infoMap.setCenter(this.getPosition());
	});

	//	auto centre map if checkbox is checked
	var autoSize = document.getElementById("autosizecb").checked;
	if (autoSize) {

		var bounds = new google.maps.LatLngBounds();

		for (var i = 0; i < allMarkers.length; i++) {
			bounds.extend(allMarkers[i].getPosition());
		}
		infoMap.fitBounds(bounds);
	}

}


/**
* 	clears all the markers on the map so new more recent ones can be added
*/
function refreshMap(){
    for (var i = 0; i < allMarkers.length; i++) {
        allMarkers[i].setMap(null);
    }

	allMarkers = [];
	locations = [];
}


/**
*		returns a string of the infromation of a bus given its location.
*		@param {float} loc - location of a bus
*   @return {string} - formatted string that is used for infoWindow display
*/
function notify(loc) {
	var name;
	var time;
	var testLatLng;

	for (var i = 0; i < locations.length; i ++) {

		testLatLng = [locations[i][1], locations[i][2]];

		if ((testLatLng[0].toString()).slice(0, 9) == (loc.toString()).slice(1, 10)){
		name = locations[i][0];
		time = locations[i][3];
		}
	}

	return "<b>Bus id: "+name + "</b> <br>Trip start time: " + time + "<br>Co-ordinates: " + loc;
}
