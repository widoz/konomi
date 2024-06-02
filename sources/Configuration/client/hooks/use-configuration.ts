/**
 * External dependencies
 */
import type Konomi from '@konomi/types';
/**
 * Internal dependencies
 */
import { initConfiguration } from '../init-configuration';

// TODO Introduce Zod Lib?
// TODO Add Immutable Map?
export function useConfiguration(): Konomi.Configuration {
	const configuration = initConfiguration();
	return {
		...configuration,
		iconsPathUrl: new URL( configuration.iconsPathUrl ?? '' ),
	};
}
