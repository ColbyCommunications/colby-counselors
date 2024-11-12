// Initialize maps
var map1 = simplemaps_usmap.create();
var map2 = simplemaps_worldmap.create();

const getUrlParameter = (name) => new URLSearchParams(window.location.search).get(name);

function zoomToRegion(region) {
	if (region) {
		// First, check if the region exists in map1.regions
		if (map1.regions && map1.regions.hasOwnProperty(region)) {
			console.log('Zooming to region:', region);
			map1.region_zoom(region);
		} else if (map1.states) {
			// If region not found, check if it's a transformed state name
			for (let state in map1.states) {
				if (map1.states.hasOwnProperty(state)) {
					const stateName = map1.states[state].sm.name.toLowerCase().replace(/\s+/g, '-');
					if (stateName === region) {
						console.log('Zooming to state:', state);
						map1.state_zoom(state);
						break;
					}
				}
			}
		}
	} else {
		console.warn('Region parameter not found in URL.');
	}
}

// Assign region once map1 completes its loading process
map1.hooks.complete = function() {
	console.log(map1);
	const region = getUrlParameter('territories');
	console.log('Region parameter:', region);
	zoomToRegion(region);
};

map1.hooks.back = function() {
	const region = getUrlParameter('territories');
	const currentUrl = window.location.href.split('?')[0];
	if (map1.regions && map1.regions.hasOwnProperty(region)) {
		window.location.href = currentUrl; // If region is valid, reload the base URL
	} else if (map1.states) {
		// If region corresponds to a state, update the URL with the state's region
		for (let state in map1.states) {
			if (map1.states.hasOwnProperty(state)) {
				const stateName = map1.states[state].sm.name.toLowerCase().replace(/\s+/g, '-');
				if (stateName === region) {
					window.location.href = `${currentUrl}/?territories=${map1.states[state].sm.region}`;
					break;
				}
			}
		}
	}
};
