// Initialize maps
var map1 = simplemaps_usmap.create();
var map2 = simplemaps_worldmap.create();

let currentState;
let currentRegion;

const updateRegionInAlpine = (region) => {
	const event = new CustomEvent('regionUpdated', {
		detail: region,
	});
	window.dispatchEvent(event);
};

const clickRegion = (region) => {
	map1.region_zoom(region);

	const currentUrl = new URL(window.location.href);
	currentUrl.searchParams.set('territories', region);
	window.history.pushState({}, '', currentUrl.toString());

	updateRegionInAlpine(region);
	currentRegion = region;
};

const clickState = (state) => {
	map1.state_zoom(state.abbreviation);

	const currentUrl = new URL(window.location.href);
	currentUrl.searchParams.set('territories', state.slug);
	window.history.pushState({}, '', currentUrl.toString());

	// Dispatch event to update Alpine region data
	updateRegionInAlpine(state.slug);
	currentState = state.slug;
};

map1.hooks.back = () => {
	const currentUrl = new URL(window.location.href);
	switch (map1.zoom_level) {
		case 'state':
			currentUrl.searchParams.set('territories', currentRegion);
			window.history.pushState({}, '', currentUrl.toString());

			updateRegionInAlpine(currentRegion);
			break;

		case 'region':
			currentUrl.searchParams.delete('territories');
			window.history.pushState({}, '', currentUrl.toString());

			updateRegionInAlpine();
			break;

		default:
			console.log('Zoom level is undefined or unexpected. Apply default logic.');
			break;
	}
};

window.transformDisplayText = function(text) {
	if (!text) return 'All Counselors'; // Fallback for empty string

	// Special case for 'mid-atlantic'
	if (text.toLowerCase() === 'mid-atlantic') {
		return 'Mid-Atlantic';
	}

	// Replace dashes with spaces, capitalize the first letter of each word
	return text
		.split('-')
		.map((word) => word.charAt(0).toUpperCase() + word.slice(1))
		.join(' ');
};
