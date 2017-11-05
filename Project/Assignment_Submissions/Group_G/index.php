<?php
$active = "home";
require_once 'include/header.php';
require_once 'include/config.php';
require_once 'requests.php';
$conn = new mysqli($hostname, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM routes";
$result = $conn->query($sql);
echo '<select id="DROP DOWN ROUTE", onchange = "refreshMarkerLocations(this.value)">'; //opens drop down box
$query = "SELECT distinct route_short_name FROM `akl_transport`.`routes`";
if ($result=$conn->query($query)) {
    echo '<option value="Select a route">Select a route</option>';
    while($row = $result->fetch_assoc()) {
    echo '<option value="'.$row['route_short_name'].'">'.$row['route_short_name'].'</option>';
}         
} else {
    echo "0 results";
}	
	echo '</select>'; //closes the drop doqn box
$conn->close();
?>

<div id="map"></div>

<script src="scripts/map.js"></script>
<script>
var nIntervalId;

function showVehicles(str) {
   //lert("You selected Route "+str);
    if (str == "") {
        document.getElementById("map").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                  initMap();       //initialize map and clear all previously loaded markers
                  var target =JSON.parse(this.responseText);    //convert response to a json object
                  if(target.response.entity!=null){
                    markers = setMarkers(target.response.entity);  
                  }else{
                    alert('No vehicles running on this route!');     //inform user no bus running on the selected route
                  }
            }
        };
        xmlhttp.open("GET","getTrip.php?q="+str,true);
        xmlhttp.send();
    }
}

function refreshMarkerLocations(str){    //reload markers location every 30 seconds
  clearInterval(nIntervalId);

   showVehicles(str);
   nIntervalId = setInterval(showVehicles,30000,str)
}


</script>



<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyWgwz17ZqlktEYZ9P8SvdxGDFghGDx8k&callback=initMap">
</script>




<?php
require_once 'include/footer.php';
?>

