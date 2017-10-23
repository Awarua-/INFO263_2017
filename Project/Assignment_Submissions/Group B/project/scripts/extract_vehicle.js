function json_extractor(vehicle_positions_raw) {
	//Initialize empty array
	var positions = []
	
	for (var i = 0; i < vehicle_positions_raw.length; i++){
		//Make JSON array readable
		var data = JSON.parse(vehicle_positions_raw[i]);
		
		//Detects if there is data inside the response
		//Extracts useful data for Google Map
		if (JSON.stringify(data["response"]).length > 2 ){
			for (var j = 0; j < data["response"]["entity"].length; j++) {
				//Creates an array of details to be used by Google Map
				var position = [data["response"]["entity"][j]["vehicle"]["vehicle"]["id"],
								parseFloat(JSON.stringify(data["response"]["entity"][j]["vehicle"]["position"]["latitude"])),
								parseFloat(JSON.stringify(data["response"]["entity"][j]["vehicle"]["position"]["longitude"])),
								data["response"]["entity"][j]["vehicle"]["trip"]["start_time"],
								Date(data["response"]["entity"][j]["vehicle"]["timestamp"]*1000).toString()];
				positions.push(position);
			}
		}	
	}
	
	return positions;
}