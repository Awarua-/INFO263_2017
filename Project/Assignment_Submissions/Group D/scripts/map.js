// Initialization of the Google Map centred on Auckland

function initMap() {
    var auckland = {lat: -36.849, lng: 174.763};
    map = new google.maps.Map(document.getElementById('map'), {
        center: auckland,
        zoom: 12
    });

//Google Maps infoWindow functions to display trip data on vehicle location markers

infoWindow = new google.maps.InfoWindow;

    // Change this depending on the name of your PHP or XML file
/*    downloadUrl('http://localhost/info/vehicle_markers.xml', function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');
        Array.prototype.forEach.call(markers, function(markerElem) {
            var id = markerElem.getAttribute('id');
            var name = markerElem.getAttribute('id');
            var address = markerElem.getAttribute('address');
            var type = markerElem.getAttribute('type');
            var point = new google.maps.LatLng(
                parseFloat(markerElem.getAttribute('lat')),
                parseFloat(markerElem.getAttribute('lng')));
            var start_time = markerElem.getAttribute('start_time');
            var timestamp = markerElem.getAttribute('timestamp');

            var infowincontent = document.createElement('div');
            var strong = document.createElement('strong');
            strong.textContent = name
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));

            var text = document.createElement('text');
            text.textContent = address
            infowincontent.appendChild(text);
            /!*var start_time_info = document.createElement('text');
            start_time_info.textContent = start_time
            infowincontent.appendChild(start_time_info);
            var timestamp_info = document.createElement('text');
            timestamp_info.textContent = timestamp
            infowincontent.appendChild(timestamp_info);*!/
            var icon = customLabel[type] || {};
            var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
            });
            marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
            });
        });
    });*/
}



/*
function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
        new ActiveXObject('Microsoft.XMLHTTP') :
        new XMLHttpRequest;

    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
        }
    };

    request.open('GET', url, true);
    request.send(null);
}


function doNothing() {}*/