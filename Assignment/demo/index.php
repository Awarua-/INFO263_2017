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
<div id="route_selection">
    <label for="routes" class="select">Select Route</label>
    <select name="routes" id="routes"></select>
</div>
<div id="debug"></div>
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

    function routeHandler(target, ui) {
        console.log(target);
        // $(".route_item#"+ target.id).css("selected", true);
    }

    $(document).ready(function() {
        var options = {
            beforeSubmit:  addParams,  // pre-submit callback
            success:       update,  // post-submit callback
            dataType:  "json"
        };
        $('#vehicle_lookup').ajaxForm(options);

        var params = { query: "routes" };
        $.post("query.php", params, function(data) {
            try {
                data = JSON.parse(data);
            }
            catch (e) {
                $("#debug").html(data);
            }
            for (var item of data) {
                $("#routes").append(new Option(item.route_short_name, item.route_short_name));
            }
            $("#routes").on("change", function (e) {
                var optionSelected = $("option:selected", this);
                updateRoute(optionSelected[0].value);
            });

        });
    });

    function updateRoute(value) {
        $.post("query.php", { query: "route_lookup", data: value}, function(data) {
            try {
                data = JSON.parse(data);
            }
            catch (e) {
                $("#debug").html(data);
            }
            console.log(data);
        });
    }

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

</script>
<?php
require_once 'include/footer.php';
?>
