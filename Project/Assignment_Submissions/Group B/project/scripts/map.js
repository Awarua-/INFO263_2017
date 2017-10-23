//Initialize global variables to be accessed around the domain
var map;
var markers = [];

function initMap()
//Set Google Map centered at Auckland
{
	map = new google.maps.Map(document.getElementById('map'), {
    	zoom: 12,
    	center: {lat: -36.8802, lng: 174.7616}, //coordinates for  Auckland
    	scaleControl: true
    });
}



function setMarkers(locations)
//Generate markers with each marker having a tooltip to display extra info
{
	//remove all markers before placing new ones
	if (markers.length != 0){
		for (i = 0; i < markers.length; i++){
			markers[i].setMap(null);
		}
		markers = []
	}
	
	var marker, i;  
    var infowindow = new google.maps.InfoWindow();  
    //Generate pointers for all busses based on bus's position
   	for (i = 0; i < locations.length; i++) {
   		marker = new google.maps.Marker({
        	position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        	map: map
        })
    	// Opens up an infowindow (Tooltip) when a marker is clicked
      	google.maps.event.addListener(marker, 'click', (function(marker, i) {
        	return function() {
          		infowindow.setContent("<div class="
          		+ "toolTip"
          		+ "><strong>Vehicle ID: </strong>"+ locations[i][0]
          		+ "<br/><strong>Start time: </strong>"
          		+ locations[i][3] + "<br/><strong>Time Stamp: </strong>"
          		+ locations[i][4].slice(0,24) +"</div>");
          		infowindow.open(map, marker);
        	}
		})(marker, i));
		autoResize(map, locations);
		markers.push(marker);
	}
}
	      
function autoResize(map, locations)
// GMap auto resize to include all markers
{
	var bounds = new google.maps.LatLngBounds();
	for (var i = 0; i < locations.length; i++) {
		bounds.extend(new google.maps.LatLng(locations[i][1], locations[i][2]));
	}
	map.fitBounds(bounds);
}