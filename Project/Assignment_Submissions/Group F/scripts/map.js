var map;
var points = [];
var data_response = [];

function initMap() {
    var mapProp = {
        center:new google.maps.LatLng(-39.915, 116.404),
        zoom:5,
        mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
}

var setVeiwPort = function () {
    var bounds = new google.maps.LatLngBounds();
    for(var i = 0;i < markers.length;i++){
        bounds.extend(markers[i].getPosition());
    }
    gmap.fitBounds(bounds);
};

function setFlag(lat, lng){
    var myLatlng = new google.maps.LatLng(lat,lng);
    var mapOptions = {
        zoom: 4,
        center: myLatlng
    }
    var marker = new google.maps.Marker({
        position: myLatlng,
    });
    marker.setMap(map);
}