<?php
$active = "home";
include 'include/header.php';
?>
<div id="map"></div>
<div id="info">

</div>
<script>
  function initMap() {
    var uluru = {lat: -25.363, lng: 131.044};
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 4,
      center: uluru
    });
    var marker = new google.maps.Marker({
      position: uluru,
      map: map
    });
  }
</script>
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1dh0Oru6bbn2-m9jyurjupgt6AgJWbyc&callback=initMap">
</script>
<script>
    var params = { mmsi: "512071000", protocol: "json", msgtype: "extended" };
    $.post("shiplookup.php", params, function(data) {
        console.log(data);
        $("#info").html(data);
    });
</script>
<?php
include 'include/footer.php';
?>
