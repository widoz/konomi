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
		// @ts-expect-error
		isDebugMode: false,
		..._configuration,
		iconsUrl: new URL( _configuration.iconsUrl ),
	};
}
