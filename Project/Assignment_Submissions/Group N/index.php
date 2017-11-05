<?php
// This is the index.php file for INFO263 GroupDev N
$active = "home";
require_once 'include/header.php';
require_once 'database.php';

?>

<script>

	// We use a .ready here so that our function is ready once the page has finished loading.
    $(document).ready(function() {
		$('#route_picker').on('change',function(){
			var route = apiQuery();
		})
    });
    
</script>

<html>

  <head>
  
  	<!--CSS relavent code goes here -->
	<link rel="stylesheet" href="CSS/master.css" type="text/css">
	<script async defer
	  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBajjU1EoGpVz0QKgSL2c_aS6vJDb8N5cA&callback=initMap">
	</script>
	<script src="scripts/map.js"></script>
	
  </head>
  
  <body>
  
	<div id="header">
	
	<!--Header for the name and logo-->
	<h1>GoBus Transport App   <img src="Media/Auckland_Transport_Logo.png" alt="Logo" width="80" height="80" align="middle"></h1>
  	
  	<!--Initialize drop down menu--> 
	<select dropMenu Name='route_picker' id='route_picker'> 
		<option id="default_route" value="set">Select</option>
		<!--We have PHP code here so that we can populate the drop down menu with all the routes available-->
		<?php
		populateRoutes($conn);
		?>	
		
	</select>
    
	</div>
	
	<div id="map"></div>
	
  </body>
  
</html>

<?php
require_once 'include/footer.php';
?>
