var map,
    bounds,
    markers = [],
    interval = null,
    current_route_name;

function initMap() {
  var auckland = {lat: -36.8485, lng: 174.7633};
  bounds = new google.maps.LatLngBounds();
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 10,
    center: auckland,
    type: 'roadmap'
  });
}

function updateRoute(value) {
    current_route_name = value;
    if (interval !== null) {
        clearInterval(interval);
    }
    routeCall(value);
    interval = window.setInterval(routeCall.bind(this), 30000, value);
}

function routeCall(value) {
    $.post("database.php", { query: "route_lookup", data: value}, function(data) {
        try {
            data = JSON.parse(data);
        }
        catch (e) {
            $("#debug").html(data);
        }

        updateRouteMarkers(data);
    });
}

function updateRouteMarkers(data) {
    clearMap();
    if (data.length > 0) {
        markers = [];
        var infoWindow = new google.maps.InfoWindow(), marker, i;

        for (i = 0; i < data.length; i++) {
            var position = new google.maps.LatLng(data[i].vehicle.lat, data[i].vehicle.lng);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                label: {
                    text: data[i].vehicle.id
                }
            });

            markers.push(marker);

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infoWindow.setContent(data[i].trip.trip_id + ', ' + data[i].trip.start_time + ', ' + data[i].timestamp);
                    infoWindow.open(map, marker);
                }
            })(marker, i));

            map.fitBounds(bounds);
        }
        $("#vehicleNum").html(data.length + " vehicles found")
    }
    else {
        $("#vehicleNum").html("No vehicles on " + current_route_name + " route :(")
    }
}

function clearMap() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    bounds = new google.maps.LatLngBounds();
}
