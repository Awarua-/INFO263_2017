var map;
var bounds;
var infowindow;
var routeId;

function initMap() {
    var auckland = { lat: -36.8485, lng: 174.7633 };
    infowindow = new google.maps.InfoWindow();
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: auckland
    });
    bounds = new google.maps.LatLngBounds();
}

function addMarkersToMap(markerWithDetail) {
    var positionToAdd = transformToPosition(markerWithDetail);
    var marker = new google.maps.Marker({
        position: positionToAdd,
        map: map
    });
    google.maps.event.addListener(marker, 'click', function () {
        infowindow.setContent(
            `<div><strong>Latitude:${positionToAdd.lat}</strong><br></div>
             <div><strong>Longitude:${positionToAdd.lng}</strong><br></div>
             <div><strong>Vehicle Id:${markerWithDetail.vehicleId}</strong><br></div>
             `
        );
        infowindow.open(map, this);
    });
    extendBounds(transformToPosition(markerWithDetail));
    map.fitBounds(bounds);
    map.panToBounds(bounds);
}

function transformToPosition(markerWithDetail) {
    return {
        lat: parseFloat(markerWithDetail.position.latitude),
        lng: parseFloat(markerWithDetail.position.longitude)
    };
}

function extendBounds(position) {
    loc = new google.maps.LatLng(position);
    bounds.extend(loc);
}
