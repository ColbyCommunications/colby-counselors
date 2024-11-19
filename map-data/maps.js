// Initialize maps
var map1 = simplemaps_usmap.create();
var map2 = simplemaps_worldmap.create();

const updateRegionInAlpine = (region) => {
	const event = new CustomEvent('regionUpdated', {
		detail: region,
	});
	window.dispatchEvent(event);
};

const clickRegion = (region) => {
	console.log(`Zooming to: ${region}`);
	map1.region_zoom(region); // Zooming in on the map

	// Update the URL with the selected region
	const currentUrl = new URL(window.location.href);
	currentUrl.searchParams.set('territories', region);
	window.history.pushState({}, '', currentUrl.toString());

	// Dispatch event to update Alpine region data
	updateRegionInAlpine(region);
};
