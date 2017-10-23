var map;

function initMap() {

	var Auckland = {lat: -36.848461, lng:174.763336 };
    map = new google.maps.Map(document.getElementById('map'), {
    	zoom: 12,
        center: Auckland,
        mapTypeId: 'terrain'
    });
    
    //Listener for auto resizing
    google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
	  
		if (this.getZoom() > 16) {
			  this.setZoom(16);
		}
	});
}


function initialiseRoute(vehicleLocation) {
	
	var infowindow = new google.maps.InfoWindow({});

	var vehicleKeys = Object.keys(vehicleLocation);
	var i = 0;
	var tempVehicle;
	var tempKey;		
	var mapBounds = new google.maps.LatLngBounds();
	
	while(i < vehicleKeys.length){

		tempKey = vehicleKeys[i];
		tempVehicle = vehicleLocation[tempKey];		
		
		
		//Add marker
		var coords = new google.maps.LatLng(tempVehicle["latitude"], tempVehicle["longitude"]);
		var marker = new google.maps.Marker({
			position: coords,
			map: map,
		});
		marker.vehicle = tempVehicle;
		//Edit map bounds to account for marker
		mapBounds.extend(marker.getPosition());
		//Add infowindow
		google.maps.event.addListener(marker, 'click', function() {
        	infowindow.setContent(generateIWHTML(this.vehicle));
        	infowindow.open(map, this);
    	});
		i++;
	}
	
		map.fitBounds(mapBounds);

	if (vehicleKeys.length == 0){
		vehiclesDetected = false;
	} else {
		vehiclesDetected = true;
	}
}

function generateIWHTML(tempVehicle) {

	//Creates HTML for a given vehicle's info window
	var contentString = "<html><div><h1>Vehicle " + tempVehicle["ID"] + "</h1></div><div><p>Latitude: "
	+ tempVehicle["latitude"] + "<br/>Longitude: " + tempVehicle["latitude"]
		
	if (tempVehicle["bearing"] != null) {
		contentString += "<br/>Bearing: " + tempVehicle["bearing"];
	}
	if (tempVehicle["lastUpdated"] != null) {
		contentString += "<br/>Last updated: " + tempVehicle["lastUpdated"];
	}

	contentString + "</p></div></html>";
		
	return contentString;
}



function updateMap(locations, geoJSONData){
	console.log(locations);
	initialiseRoute(locations);
	drawRoute(geoJSONData);
	return true;
}

function parseShapeData(data) {
    shapeData = JSON.parse(data);
    
    var Buffer = require('buffer').Buffer;
    var wkx = require('wkx');
    
    var wkbBuffer = new Buffer(shapeData.response[0].the_geom, 'hex');
    var geometry = wkx.Geometry.parse(wkbBuffer);
    
    var intro = '{ "type": "Feature", "geometry":'
    var gj = geometry.toGeoJSON();
    var end = "}";
    
    var geo_string = intro + JSON.stringify(gj) + end; // WKX does support the feature keyword has to be added manually
    var geoJSONData = JSON.parse(geo_string);

    return geoJSONData;
}
      
function drawRoute(geoJSONData) {

	if (vehiclesDetected == false){
		var mapBounds = new google.maps.LatLngBounds();
		console.log("called");
		var last = geoJSONData.geometry.coordinates.length;
		var start = geoJSONData.geometry.coordinates[0]; //start of route
		var q1 =  geoJSONData.geometry.coordinates[Math.floor(last/4)]; //one quarter in route
		var half = geoJSONData.geometry.coordinates[Math.floor(last/2)]; //mid point of route
		var q3 =  geoJSONData.geometry.coordinates[Math.floor(last/4)*3];//three quarter in route
		var end = geoJSONData.geometry.coordinates[last - 1]; //final point in route

		var point1 = new google.maps.LatLng(start[1], start[0]);
		var point2 = new google.maps.LatLng(q1[1], q1[0]);
		var point3 = new google.maps.LatLng(half[1], half[0]);
		var point4 = new google.maps.LatLng(q3[1], q3[0]);
		var point5 = new google.maps.LatLng(end[1], end[0]);

		mapBounds.extend(point1);
		mapBounds.extend(point2);
		mapBounds.extend(point3);
		mapBounds.extend(point4);
		mapBounds.extend(point5);

		map.fitBounds(mapBounds);

	}

    map.data.addGeoJson(geoJSONData);
}

function reloadMarkers(vehicleLocation) {
	
	var infowindow = new google.maps.InfoWindow({

    });

	var vehicleKeys = Object.keys(vehicleLocation);
	var i = 0;
	var tempVehicle;
	var tempKey;		
		
	while(i < vehicleKeys.length){

		tempKey = vehicleKeys[i];
		tempVehicle = vehicleLocation[tempKey];		

		var coords = new google.maps.LatLng(tempVehicle["latitude"], tempVehicle["longitude"]);
		var marker = new google.maps.Marker({
			position: coords,
			map: map,
		});
		marker.vehicle = tempVehicle;
		google.maps.event.addListener(marker, 'click', function() {
        	infowindow.setContent(generateIWHTML(this.vehicle));
        	infowindow.open(map, this);
    	});
		i++;
	}
}