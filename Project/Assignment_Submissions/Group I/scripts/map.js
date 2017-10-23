var map;
var infoWindows = [];
var bounds;
var markers = [];
var update;

function initMap() {
    var auckland = {lat: -36.849, lng: 174.763}; //decide the initial position of the map
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: auckland
	});
}

//close bus information windows
	function closeAllInfoWindows() {
	for (var i=0;i<infoWindows.length;i++) {
		infoWindows[i].close();
	}
}
//Show available buses currently on the map
function showVehicles() {
	if (routes_name != ' ') {
		$.post("example_request.php", {route_name: routes_name}, function(data) {
            var results = JSON.parse(data);
            if (results.length != 0) {
                bounds = new google.maps.LatLngBounds();

                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
                markers = [];

//Setting the variables for the bus information
                for (var j = 0; j < results.length; j++) {
                    var id = results[j]["id"];
                    var latitude = results[j]["latitude"];
                    var longitude = results[j]["longitude"];
                    var start_time = results[j]["start_time"];
                    var point = new google.maps.LatLng(latitude, longitude);
					
					//add icon for the bus on the map                     
					var icon = {
                        url: "include/bus_pointer.svg",
                        scaledSize: new google.maps.Size(35, 50)                     };

                    var busMarker = new google.maps.Marker({
                        map: map,
                        position: point,
                        title: 'Bus: ' + id,
                        icon: icon
                    });

                    markers.push(busMarker);
					
					//Create informaion window for showing the bus information on the map
                    var infoWindow = new google.maps.InfoWindow({
                        content: "Bus ID: " + id + '<br>' + "Latitude: " + latitude + '<br>' + "Longitude" + longitude + '<br>' + "Start Time: " + start_time
                    });

                    google.maps.event.addListener(busMarker, 'click', (function (busMarker, infoWindow) {
                        return function () {
                            closeAllInfoWindows();
                            infoWindow.open(map, busMarker);
                            infoWindow.push(infoWindow);
                        };
                    })(busMarker, infoWindow));
                    bounds.extend(busMarker.getPosition());
                }
                bounds.extend(busMarker.getPosition());
                map.fitBounds(bounds); //Map resizes automatically
			} else {
                alert("No active buses on this route "); //alert if there is no available bus on the route currently
			}
		});
	}
    update = setTimeout(showVehicles, 30000); //Codes for renewing map every 30 seconds
}