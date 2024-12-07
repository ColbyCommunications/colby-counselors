var map1 = simplemaps_usmap.create();
var map2 = simplemaps_worldmap.create();

let currentState;
let currentRegion;

window.activeTab;

var activeMap;

const eventRefresh = new CustomEvent('mapRefresh');

const setActiveMap = new CustomEvent('setActiveMap');

const updateRegionInAlpine = (region) => {
	const event = new CustomEvent('regionUpdated', {
		detail: region,
	});
	window.dispatchEvent(event);
};

window.addEventListener('setActiveMap', (event) => {
	console.log('activemap');
	if (window.activeTab === 'us') {
		activeMap = map1;
	} else {
		activeMap = map2;
	}
});

window.addEventListener('mapRefresh', (event) => {
	activeMap.load();
});

window.addEventListener('mapZoom', (event) => {
	setTimeout(() => {
		activeMap.region_zoom(event.detail);
	}, 500);
});

const clickRegion = (region) => {
	console.log(activeMap);
	activeMap.region_zoom(region);

	const currentUrl = new URL(window.location.href);
	currentUrl.searchParams.set('territory', region);
	window.history.pushState({}, '', currentUrl.toString());

	updateRegionInAlpine(region);
	currentRegion = region;
};

const clickState = (state) => {
	activeMap.state_zoom(state.abbreviation);

	const currentUrl = new URL(window.location.href);
	currentUrl.searchParams.set('territory', state.slug);
	window.history.pushState({}, '', currentUrl.toString());

	// Dispatch event to update Alpine region data
	updateRegionInAlpine(state.slug);
	currentState = state.slug;
};
setTimeout(() => {
	console.log(activeMap);
	activeMap.hooks.back = () => {
		const currentUrl = new URL(window.location.href);
		switch (activeMap.zoom_level) {
			case 'state':
				currentUrl.searchParams.set('territory', currentRegion);
				window.history.pushState({}, '', currentUrl.toString());

				updateRegionInAlpine(currentRegion);
				break;

			case 'region':
				currentUrl.searchParams.delete('territory');
				window.history.pushState({}, '', currentUrl.toString());

				updateRegionInAlpine();
				break;

			default:
				console.log('Zoom level is undefined or unexpected. Apply default logic.');
				break;
		}
	};
}, 500);
