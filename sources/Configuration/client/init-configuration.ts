/**
 * External dependencies
 */
import type Konomi from '@konomi/types';

let configuration: Konomi.Configuration | null = null;

export function initConfiguration(
	serializedConfiguration: string
): Konomi.Configuration {
	if ( ! configuration ) {
		configuration = parseConfiguration( serializedConfiguration );
	}

	assertConfiguration( configuration );
	return configuration;
}

function assertConfiguration(
	parsedConfiguration: unknown
): asserts parsedConfiguration is Konomi.Configuration {
	if ( ! Boolean( parsedConfiguration ) ) {
		throw new Error( 'Configuration not initialized' );
	}
}

function parseConfiguration(
	serializedConfiguration: string
): Konomi.Configuration {
	try {
		return JSON.parse( serializedConfiguration );
	} catch ( error ) {
		throw new Error(
			`Konomi invalid configuration: ${ extractErrorMessageFromError(
				error
			) }`
		);
	}
}

// eslint-disable-next-line complexity
function extractErrorMessageFromError( error: unknown ): string {
	let errorMessage = typeof error === 'string' ? error : undefined;
	errorMessage = error instanceof Error ? error.message : errorMessage;

	return errorMessage ?? 'unknown error';
}