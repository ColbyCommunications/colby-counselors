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

window.addEventListener('setMapDescriptions', (event) => {
	console.log(event);
	let statesObj = activeMap.mapdata.state_specific;

	let usTerritoryCounselor = event.detail.counselors.find(
		(c) => c.terms.territories && c.terms.territories.some((t) => t.slug === 'us-territories'),
	);
	event.detail.counselors.forEach((counselor) => {
		for (const key in statesObj) {
			if (key === 'PR' || key === 'GU' || key === 'VI' || key === 'AS' || key === 'MP') {
				statesObj[
					key
				].description = `<img src="${usTerritoryCounselor.thumbnail}" style="width: 200px"/><span style="font-size: 20px;">${usTerritoryCounselor.meta.first_name} ${usTerritoryCounselor.meta.last_name}</span><br>${counselor.meta.job_title}`;
			} else if (
				counselor.terms.territories &&
				counselor.terms.territories.some(
					(terr) => terr.slug === statesObj[key].name.replace(/\s+/g, '-').toLowerCase(),
				)
			) {
				statesObj[
					key
				].description = `<img src="${counselor.thumbnail}" style="width: 200px"/><span style="font-size: 20px;">${counselor.meta.first_name} ${counselor.meta.last_name}</span><br><span style="font-size: 18px;">${counselor.meta.job_title}</span>`;
			}
		}
	});
	activeMap.load();
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
