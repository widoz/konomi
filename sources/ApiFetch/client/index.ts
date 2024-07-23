import { configuration } from '@konomi/configuration';

const { apiFetch: _apiFetch } = window.wp;

// The parameters for the middleware exist outside of our codebase.
// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
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
