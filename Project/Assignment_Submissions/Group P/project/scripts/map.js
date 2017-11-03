function initMap() {
    var uluru = {lat: -36.8485, lng: 174.7633}; //-36.848461, 174.763336 36.5151° S, 174.6628°
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: uluru
    });
    var marker = new google.maps.Marker({
        position: uluru,
        map: map
    });

    var pukekohe = {lat: -37.2021, lng: 174.9035};

    var bounds = new google.maps.LatLngBounds();
    bounds.extend(uluru);
    bounds.extend(pukekohe);
    map.fitBounds(bounds)


}
