// This is the map.js file for INFO263 by GroupDev N

// Initialising variables
var markers = [];
var map;
//function to intialise the map        
function initMap() {
	// We have the variable auckland set up with its long/lat values so the the default centering of the map is on the city of Auckland
	var auckland = {lat: -36.849316, lng: 174.766249};
	map = new google.maps.Map(document.getElementById('map'), {
	  zoom: 12,
	  center: auckland
	});
}

// This function refreshes the map so that updated markers can be seen
function refreshMap() {
	google.maps.event.trigger(map, 'resize');
}

// Function to set markers on the map
function setMapOnAll(map) {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
    }
}

// Function that deletes the markers on the map by calling the function setMapOnAll(null)
function deleteMarkers() {
	setMapOnAll(null);
	markers = [];
}

// This is the API Query function that we use to get information about the buses
function apiQuery() {
	var query_route = $('#route_picker').val();
	if (query_route != "set") {
		
		var response;
		var ajaxRequest = new XMLHttpRequest();
		var vehicle_array;
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				response = document.getElementsByTagName('body');
				response.innerHTML = ajaxRequest.responseText;
				// We are splitting the response from the API and adding it to an array
				vehicle_array = response.innerHTML.split("},{");
				vehicle_array[0] = vehicle_array[0].substr(2);
				vehicle_array[vehicle_array.length-1] = vehicle_array[vehicle_array.length-1].slice(0, (vehicle_array[vehicle_array.length-1].length-2));
				deleteMarkers();
				console.log(response.innerHTML);
				// Initialising variables
				var lngN;
				var commaPos;
				var latN;
				var latLng;
				var marker;
				var checkString = response.innerHTML.replace(/\s+/g, '');
				
				// For loop to assign the variables required to get the information we need
				if (checkString != '[]'){
					for (var i = 0; i <= vehicle_array.length-1; i++) {
						firstSemi = vehicle_array[i].indexOf(':');
						lngN = vehicle_array[i].indexOf(':',firstSemi+1)+1;
						commaPos = vehicle_array[i].indexOf(',', lngN+1)+1;
						latN = vehicle_array[i].indexOf(':',lngN+1)+1;
						latLng = {lat: parseFloat(vehicle_array[i].slice(latN, vehicle_array[i].length-1)), lng: parseFloat(vehicle_array[i].slice(lngN, commaPos))};
						detailsIndex = vehicle_array[i].indexOf(':')+2;
						detailsIndex1 = vehicle_array[i].indexOf(',', detailsIndex)-1;
						details = "Vehicle ID: " + vehicle_array[i].slice(detailsIndex, detailsIndex1);
						
						var infowindow = new google.maps.InfoWindow({
						  content: vehicle_array[i]
						});
						
						var onMarkerClick = function() {
							var marker = this;
							infowindow.setContent( marker.details );
							infowindow.open(map, marker);
						};
						marker = new google.maps.Marker({
							position: latLng,
							map: map,
							icon: "Media/bus1.svg",
							details: details
						});
						
						google.maps.event.addListener(marker, 'click', onMarkerClick );
						
						map.panTo(latLng); 
	          			map.setZoom(11) 
						markers.push(marker);
					}
				} else {
					alert("No buses found on this route :(");
				}
			}
		}
		ajaxRequest.open("GET", "vehicle_query.php?r=" + query_route, true);
		ajaxRequest.send();
	} else {
		deleteMarkers();
	}
}

setInterval(function(){
	apiQuery()}, 30000);
       