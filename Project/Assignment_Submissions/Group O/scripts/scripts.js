/**
*	File containing various scripts that are used on the website
*/

/** Takes the string that is passed back from the databse query and
*   turns it into a javascript array
*   @param {string} string - string that needs to be converted to array
*   @return {array} array - array that is converted from the string
*/
function stringToArray(string) {
	string = string.slice(1, -1);
	var array = string.split(",");

	return array;
}


/**
*		Sends a POST request to the server in order to obtain the routes from
*   the akl-transport database
*   @return {array} result - route array
*/
function routeQuery() {
	$.post('postRequests.php', {'allRoutes': true}, function(result) {
		populateRoutes(result);
	});
}


/**
*   Use some jQuery to fill the select box in the html
*   @param {array} response - the response that the route query returns
*/
function populateRoutes(response) {
	response = stringToArray(response);
	for (var i = 0; i < response.length; i++) {
		var value = response[i].slice(1, -1);
		$('#routeSelect').append('<option value="'+value+'">'+value+'</option>');
	}
}


/**
*   Sends a POST request to query the Auckland Transport API. This then queries
*   the akl-tranport database to get the required trip ids
*
*   @return {JSON} result - the resulting JSON string that has infromation about
*										the busses that are(nt) on the route
*/
function APIquery() {
	var queryRoute = $('#routeSelect').val();
	if (queryRoute != 'None') {
		$.post('postRequests.php', {'queryAPI': true,'route':queryRoute}, function(result) {
			if(result.length > 0) {
				extractLocations(result);
				$('#routeOutput').text('There are ' + result.length + ' busses on the '+ queryRoute + ' route. Click on a bus for more information.');
			} else {
				noBussesFound(queryRoute);
			}
		});
	}
}


/**
*		Extracts the locations from the JSON and adds them to the map
*		@param {JSON} param - the JSON object which contains bus info
*/
function extractLocations(param) {
	var i;
	var len = param.length;
	refreshMap();
	for (i = 0; i < len; i++) {
		newmarker(param[i].lat, param[i].long, param[i].id, param[i].time);
	}

}


/**
* 	Resets the map to focus on central Auckland if no busses are on the route.
*   A message is also displayed on the screen
*		@param {string} queryRoute - the route that is being queried
*/
function noBussesFound(queryRoute) {
	refreshMap();
	$('#routeOutput').text('No busses found for route: ' + queryRoute);
	if ($('#autosizecb').prop('checked')) {
		infoMap.setCenter(initialPoint);
		infoMap.setZoom(15);
	}
}
