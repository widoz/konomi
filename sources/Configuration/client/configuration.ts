/**
 * External dependencies
 */
import type Konomi from '@konomi/types';
import { initConfiguration } from './init-configuration';

export function configuration(): Konomi.Configuration {
	const _configuration = initConfiguration( '' );
	return {
		..._configuration,
		iconsPathUrl: new URL( _configuration.iconsPathUrl ),
	};
}
