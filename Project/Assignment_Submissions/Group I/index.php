<?php
$active = "home";
require_once 'include/header.php';
?>

<link href="css/map.css" type="text/css" rel="stylesheet">

<script>

    var routes_name;

    $(document).ready(function() {
        $('#drop_down_list').change(function() {
            var route = $('#drop_down_list option:selected');
            routes_name = route.val(); //The name of selected route
            showVehicles();
        });
    });
</script>

	
<div>
	<select id="drop_down_list""> 
        <option value="" disabled selected>---Select routes---</option>
        <?php
        #Import data from SQL file
        require_once 'include/config.php';
        require_once 'requests.php';
        $conn = new mysqli($hostname, $username, $password, $database); //connect to the database
        if ($conn->connect_error) die($conn->connect_error);

        $query = "SELECT DISTINCT route_short_name FROM routes"; //Select data from the specific row
        $dbresult = $conn->query($query);
        while ($row = mysqli_fetch_array($dbresult)) {
            $route_short_name=array();
            $route_short_name = $row['route_short_name'];
            echo '<option value="'.$route_short_name.'">'.$route_short_name.'</option>';
        }
    ?>
    </select>

</div>

<div id="map"></div>

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCnRgdLoZ68boj0j2s4ysgF8QpA2MfOAdY&callback=initMap">
</script>
	    
<script src="scripts/map.js"></script>

<?php
require_once 'include/footer.php';
?>