<?php
$active = "home";
require_once 'include/header.php';
?>
<div id="map"></div>
<div id="route_selection">
    <label for="routes" class="select">Select Route</label>
    <select name="routes" id="routes"></select>
</div>
<div id="vehicleNum"></div>
<div id="debug"></div>
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1dh0Oru6bbn2-m9jyurjupgt6AgJWbyc&callback=initMap">
</script>
<script src="scripts/map.js"></script>
<script>
    $(document).ready(function() {
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
</script>
<?php
require_once 'include/footer.php';
?>
