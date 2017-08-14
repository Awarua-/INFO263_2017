<?php
$active = "home";
require_once 'include/header.php';
?>
<div id="map"></div>
<div id="info">
    <form id="vehicle_lookup" action="vehiclelookup.php" method="post">
        Vehicle ID: <input type="text" name="vehicle_id" />
        <input type="Submit" value="Submit"/>
    </form>
</div>
<div id="routes">

</div>
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1dh0Oru6bbn2-m9jyurjupgt6AgJWbyc&callback=initMap">
</script>
<script>
    var map;
    function initMap() {
      var uluru = {lat: -25.363, lng: 131.044};
      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: uluru
      });
      var marker = new google.maps.Marker({
        position: uluru,
        map: map
      });
    }

    $(document).ready(function() {
        var options = {
            beforeSubmit:  addParams,  // pre-submit callback
            success:       update,  // post-submit callback
            dataType:  "json"
        };
        $('#vehicle_lookup').ajaxForm(options);
    });
    function addParams(arr, $form, options) {
        arr.push({name: "type", value:"realtime", type:"text"});
        return true;
    }

    function update(responseText, statusText, xhr, $form) {
        if (responseText.response !== []) {
            console.log(responseText.response);
            var vehicle = responseText.response.entity[1].vehicle;
            var latLng = {lat: vehicle.position.latitude, lng: vehicle.position.longitude};

            var marker = new google.maps.Marker({
                position: latLng,
                title:vehicle.vehicle.id
            });
            marker.setMap(map);
        }
    }

    var params = { query: "routes" };
    $.post("query.php", params, function(data) {
        console.log(data);
        $("#routes").html(data);
    });

</script>
<?php
require_once 'include/footer.php';
?>
