const { apiFetch: _apiFetch } = window.wp;

_apiFetch.use( ( options, next ) => {
	const result = next( options );

	result.catch( ( error ) => {
		// eslint-disable-next-line no-console
		console.log( error );
	} );

	return result;
} );

export const apiFetch = _apiFetch;
