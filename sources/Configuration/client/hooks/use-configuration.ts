/**
 * External dependencies
 */
import type Konomi from '@konomi/types';

// TODO Introduce Zod Lib?
// TODO Add Immutable Map?
export function useConfiguration(): Konomi.Configuration {
	// @ts-ignore
	const configuration: Partial< Konomi.Configuration > = window['konomi'] ?? {}

	return {
		...configuration,
		iconsPathUrl: new URL( configuration.iconsPathUrl ?? '' ),
	};
}
