var map;
var markers =[];
function initMap() {
        var auckland = {lat: -36.8485, lng: 174.7633};
         map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: auckland
        });
        var marker = new google.maps.Marker({
        position: auckland,
        map: map
        });
        markers =[];
        markers.push(marker);
        for(var i =0;i<markers.length;i++){      //clear previously loaded markers 
             markers[i].setMap(null);
        }                
        markers=[];  
        markers.push(marker);
}




function setMarkers(data){
    var customLabel = {
            bus: {
              label: 'B'  //customize marker 
            }
        };

    var bounds = new google.maps.LatLngBounds();
   // var origin = new google.maps.LatLng( -36.8485, 174.7633);          
   // bounds.extend(origin);
 //   alert("Number of buses of this route: ".concat(data.length));
     for(var i = 0;i<data.length;i++){
       (function(index){                  //refer to Matej P 's answer to https://stackoverflow.com/questions/37409872/google-map-info-window-is-only-showing-on-one-marker-in-javascript
        var lat1 = data[index].vehicle.position.latitude;
        var lon1 = data[index].vehicle.position.longitude;
      //  alert(lat1);
        var point = new google.maps.LatLng(lat1, lon1);
        bounds.extend(point);                  //resize the map to contain the newly added marker and the previous ones
        var vid = data[index].vehicle.vehicle.id;
        var icon = customLabel['bus'] || {};
        var marker = new google.maps.Marker({
           position: point,          
           map:map,
           label: icon.label     //customize the marker 
        });
      //  markers =[];
        var j = i+1;   //markers already has Auckland as first marker, so index has to increment by 1
        markers[j] = marker;   

        var infowincontent = document.createElement('div');          //refers to https://developers.google.com/maps/documentation/javascript/mysql-to-maps
        var strong = document.createElement('strong');
        strong.textContent ="Bus id: ".concat(data[index].vehicle.vehicle.id);
        infowincontent.appendChild(strong);
        infowincontent.appendChild(document.createElement('br'));
        
        var text = document.createElement('text');      //add timestamp
        text.textContent = "timestamp: ".concat(data[index].vehicle.timestamp);
        infowincontent.appendChild(text);
        infowincontent.appendChild(document.createElement('br'));

        var latInfo = document.createElement('latInfo');  //add vehicle latitude
        latInfo.textContent = "Latitude ".concat(data[index].vehicle.position.latitude);
        infowincontent.appendChild(latInfo);
        infowincontent.appendChild(document.createElement('br'));

        var lonInfo = document.createElement('lonInfo');  //add vehicle longitude
        lonInfo.textContent = "Longitude: ".concat(data[index].vehicle.position.longitude);
        infowincontent.appendChild(lonInfo);

        var infoWindow = new google.maps.InfoWindow();  //add infowindow on marker to show additional info
        marker.addListener('mouseover', function() {
          infoWindow.setContent(infowincontent);
          infoWindow.open(map, marker);
        });
        marker.addListener('mouseout',function() {
           infoWindow.close();
        });
    })(i);
    }
 
    map.fitBounds(bounds);
}