/**
 * This function performs a GET request to getAllAvailableRoutes.php for information about the available routes (returned
 * in JSON format). It then calls the function, createRouteOptions with the JSON file as an argument.
 */
getRoutes = function() {
    $.get("getAllAvailableRoutes.php", function(data) {
        createRouteOptions(data);
    });
};


/**
 * This function adds all the routes (i.e. route_long_name) to the select list (i.e. route-select).
 *
 * @param JSON routes    JSON of the routes
 */
function createRouteOptions(routes){
    $('#loading').remove(); // Remove the loading option
    $('<option id="selectRoute" value="loading">Select a route</option>').appendTo('#route-select'); // Make this the first option displayed

	var short_route_names = [];
	for (var i = 0; i < routes.length; i++) {
		short_route_names.push(routes[i]['info']['route_short_name']);
	}   
	short_route_names.sort();

    for (var j = 0; j < short_route_names.length; j++) {
		for (var i = 0; i < routes.length; i++) {
		    // Make sure that the route is not already added
		    if (short_route_names[j] == routes[i]['info']['route_short_name']) {
			    if (!$('#' + routes[i]['route_id']).length) {
			        var selectName = routes[i]['info']['route_short_name'] + ': ' + routes[i]['info']['route_long_name'];
			        $('<option id=' + routes[i]['route_id'] + '>' + selectName + '</option>').appendTo('#route-select');
			    }
		    }
		}
    }
};


///////////////////////////////////////////////////////////////////////////////
$(document).ready(function() {
	getRoutes();
});
//////////////////////////////////////////////////////////////////////////////