import { configuration } from '@konomi/configuration';

const { apiFetch: _apiFetch } = window.wp;

_apiFetch.use( ( options, next ) => {
	const { isDebugMode } = configuration();
	const result = next( options );

	result.catch( ( error ) => {
		if ( isDebugMode ) {
			// eslint-disable-next-line no-console
			console.log( error );
		}
	} );

	return result;
} );

export const apiFetch = _apiFetch;
