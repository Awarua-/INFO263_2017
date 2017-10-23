<?php
$active = "home";
require_once 'include/header.php';
require_once 'include/config.php';
require_once 'database.php';

$database=new Database($hostname, $username, $password, $database);


?>

  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzlzcQmjdGnvtn-v33rE5m5hwrlHxHRNY&callback=initMap">
  </script>
  <script src="scripts/map.js"></script>

  <script>
  
    $(document).ready(function() {
        getAllRoutes();

        setInterval(function(){
            if (routeId){
                 getMarksByRouteId(routeId);
            }
        }, 30000);

        function getAllRoutes(){
            $.get('example_request.php',
            {GetAllRoutes:true},
                function(result){
                $.each(result,function(i,item){
                    $('#routesSelect').append(
                    `<option id="${result[i].route_id}">${result[i].route_long_name}</option>`
                      );
                });
            });
        };

        // Get trips by route id
        function getMarksByRouteId(routeId){
            $.get('example_request.php',
            {
                GetMarksByRouteId:true,
                RouteId:routeId
            },
            function(result){
                initMap();
                $.each(result,function(i,item){
                    var markerWithDetail={};
                    markerWithDetail['position']=item.position;
                    markerWithDetail['vehicleId']=item.vehicleId;
                    markerWithDetail['trip']=item.trip;
                    addMarkersToMap(markerWithDetail);
                })
            });
        };

        $("select").change(function () {
            routeId=$(this).children(":selected").attr("id");
            getMarksByRouteId(routeId);
        });
    });
    </script>

    <h3>My Google Maps Demo</h3>
    <div id="map"></div>
    <form>


    <div class="form-group">
        <label for="exampleSelect2">Select Routes</label>
        <select multiple class="form-control" id="routesSelect">
        </select>
    </div>

    <div class="form-check" id="routeCheck">
    </div>

</form>

<?php
require_once 'include/footer.php';
?>
