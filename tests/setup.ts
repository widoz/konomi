module.exports = function() {
	ensureGlobalWp();
	mockWpApiFetch();
};

function ensureGlobalWp() {
	if ( typeof globalThis.wp === 'undefined' ) {
		globalThis.wp = {};
	}
}

// TODO Use __mocks__
function mockWpApiFetch() {
	const apiFetch = () => void 0;
	apiFetch.use = () => void 0;
	globalThis.wp['apiFetch'] = apiFetch;
}
