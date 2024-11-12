// Initialize maps
var map1 = simplemaps_usmap.create();
var map2 = simplemaps_worldmap.create();

// Function to get URL parameter by name
function getUrlParameter(name) {
	const urlParams = new URLSearchParams(window.location.search);
	return urlParams.get(name);
}

// Assign region once map1 completes its loading process
map1.hooks.complete = function() {
	console.log(map1);
	const region = getUrlParameter('territories'); // Get the 'territories' parameter from URL
	console.log('Region parameter:', region); // Log the region parameter for debugging

	// Check if the region exists in map1.regions or map1.states.sm
	if (region) {
		if (region === 'dc') {
			map1.state_zoom('DC');
		}
		// First, check if region matches directly in map1.regions
		if (map1.regions && map1.regions.hasOwnProperty(region)) {
			console.log('Zooming to region:', region); // Log if region is found
			map1.region_zoom(region); // Zoom to the specified region
		} else if (map1.states) {
			// If region not found, look in states by iterating
			for (let state in map1.states) {
				if (map1.states.hasOwnProperty(state)) {
					let stateName = map1.states[state].sm.name.toLowerCase().replace(/\s+/g, '-');
					// Check if transformed state name matches region
					if (stateName === region) {
						console.log('Zooming to state:', state); // Log if state is found
						map1.state_zoom(state); // Zoom using the state ID
						break;
					}
				}
			}
		} else {
			console.warn('States data not found in map1.');
		}
	} else {
		console.warn('Region parameter not found in URL.');
	}
};
