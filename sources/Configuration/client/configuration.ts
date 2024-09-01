/**
 * External dependencies
 */
import { initConfiguration } from './init-configuration';

/**
 * Internal dependencies
 */
import type { KonomiConfiguration } from './types';

export function configuration(): KonomiConfiguration.Configuration {
	const _configuration = initConfiguration( '' );
	return {
		isDebugMode: false,
		..._configuration,
		iconsPathUrl: new URL( _configuration.iconsPathUrl ),
	};
}
