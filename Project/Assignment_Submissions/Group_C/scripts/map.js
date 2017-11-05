var markers = [];
var map;

function initMap() {
    var auckland = {lat: -36.848, lng: 174.763};
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: auckland
    });

    /** 
    * When a route is selected from the toggle box
    */
    $('#route-select').on('change', function() {
        // Get the id of route from <option> in route-select
        var route_id = $('#route-select option:selected').attr('id');

        if (route_id !== "selectRoute") { // Make sure that the route_id is not the "Select a route" option:
            getBusCoordinates = function () {
                var item = 'route_id=' + route_id;
                $.get("getBusCoords.php", item)
                    .done(function (data) {
                        // Prepare the for new markers by removing the old
                        deleteMarkers();

                        // New Array for the positions on the map
                        var markersArray = new Array();

                        for(var route in data)
                        {
                            var trip_id = data[route]['trip_id'];
                            var latitude = data[route]['latitude'];
                            var longitude = data[route]['longitude'];
                            var location = {lat: latitude, lng: longitude};
                            var arrival_time = data[route]['arrival_time'];
                            var arrival_delay = data[route]['arrival_delay'];
                                                        
                            var occupancy_status = data[route]['occupancy_status'];
                            var stop_id = data[route]['stop_id'];
                            var stop_name = getStopName(stop_id);
                            var vehicle_id = data[route]['vehicle_id'];
                            var options = {hour: "numeric", minute: "numeric", second: "numeric"};
                            var date = (new Date(arrival_time*1000)).toLocaleDateString('en-NZ', options);
                            var vehicleInfo = '<div>' +
                                '<div id="vehicleInfo">' +
                                '</div>' +
                                '<h class="vehicle">Vehicle (' + vehicle_id + ')</h>' +
                                '<div><br />' +
                                '<p>' +
                                '<b>Trip ID:</b> ' + trip_id + '<br />' +
                                '<b>Arrival/Departure time:</b> ' + date + '<br />' +
                                '<b>Arrival/Departure delay:</b> ' + parseFloat(arrival_delay / 60).toFixed(2) + ' mins<br />' +
                                '<b>Occupancy status:</b> ' + occupancy_status + '<br />' +
                                '<b>Next stop:</b> ' + stop_name +
                                '</p>' +
                                '</div>' +
                                '</div>';
                            var infowindow = new google.maps.InfoWindow({
                                content: vehicleInfo
                            });

                            if (latitude !== 'undefined' && longitude !== 'undefined') {
                                // Add the location to the array of markers
                                markersArray.push(location);

                                addMarker(location, map, infowindow);
                            }
                        }

                        showMarkers();
                        resizeMap(map, markersArray);
                    })
                    .fail(function () {
                        console.log("getBusCoords.php not found or data is incorrect");
                    });
            };

            // Update vehicle position every 30 seconds
            getBusCoordinates();
            window.setInterval( function() {
                getBusCoordinates()}, 30000);
        }
    });
}

/**
 * Resizes the map to display all the bus markers on the map.
 * If there is only one bus, the map is centered on the bus, otherwise
 * calls the Google Maps API function fitBounds to resize the map
 *
 * @param Map
 * @param An array of points on the map
 */
function resizeMap(map, points) {
    var extend_location = new Array();
    var bounds = new google.maps.LatLngBounds();

    for (point in points) {
        extend_location.push(bounds.extend(points[point]));
    }
    if (points.length > 1) {
        map.fitBounds(bounds);
    }
    else if (points.length == 1) {
        map.setCenter(bounds.getCenter());
        map.setZoom(14);
    }
}

// Gets the stop name given the stop id
function getStopName(stop_id) {
    var stop_body = 'stop_id=' + stop_id;
    var stop_name = 'Not found';

    // another method of a GET request
    // Note: this method is used because it can be configured to async
    $.ajax({
        type: "GET",
        url: "getStopName.php",
        data: stop_body,
        success: function(result) {
            if (result !== null) {
                stop_name = result["stop_name"];
            }
        },
        async:false
    })
    return stop_name;
}

// Adds a marker to the map.
function addMarker(location, map, infowindow) {
    // Add the marker at the clicked location, and add the next-available label
    // from the array of alphabetical characters.
    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
    markers.push(marker);

    marker.addListener('click', function() {
        infowindow.open(map, marker);
    })
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() {
    setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
    clearMarkers();
    markers = [];
}

google.maps.event.addDomListener(window, 'load', initMap);
google.maps.event.trigger(map, "resize");

