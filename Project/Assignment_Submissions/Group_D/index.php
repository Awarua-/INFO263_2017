<?php
$active = "home";
require_once 'include/header.php';
?>
<link href="css/map.css" type="text/css" rel="stylesheet">
<link href="css/chosen.css" type="text/css" rel="stylesheet">
<script src="scripts/map.js"></script>
<script src="scripts/chosen.jquery.js"></script>
<script src="ajax.js"></script>

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgDsjTAoG9ylNSKi3X_6T2hANiuHkDmQE&callback=initMap">
</script>

<style>
    #intro, h1 {
        max-width: 850%;
        padding-top: 20px;
    }

#route_list, form {
    display: block;
    padding-top: 20px;
    }

</style>

<div id="map">
</div>

<?php
require_once 'include/functions.php';
$routes = get_routes();
?>


<form>
    <label for="route_list"><h3>Select a Route<br></h3></label>
    <select id="route_list" class="">
        <option selected="selected"></option>
        <?php
            for ($j = 0; $j < count($routes); ++$j) { ?>
                <option value="<?= $routes[$j]?>"><?= $routes[$j]?></option>
                <?php
            }
        ?>
    </select>
</form>

<div id="intro">
    <h3>Auckland Transport Application Prototype</h3>
    <p>The Auckland Transport Application prototype displays the real-time location of buses currently operating in the Greater Auckland region.<br>
        Search for a bus using its route number to see the current locations of all buses travelling on that route. <br>
        The map will automatically update positions every 30 seconds</p>
</div>

<?php
require_once 'example_request.php';
require_once 'include/functions.php';
require_once "include/xml_generator.php";
//$trip_ids = get_trip_ids($route_id);
//$json = json_decode(runApiCall($trip_ids), true);
//$vehicles = vehicle_json_parser($json);
//generate_xml($vehicles);
?>

<?php
require_once 'include/footer.php';

?>