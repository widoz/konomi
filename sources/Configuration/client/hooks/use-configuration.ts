/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
/**
 * External dependencies
 */
import type Konomi from '@konomi/types';

// TODO Introduce Zod Lib?
export function useConfiguration(): Konomi.Configuration {
	const configuration: Partial< Konomi.Configuration > = useSelect(
		( select ) => select( 'core' ).getSite().konomi ?? {}
	);

	return {
		...configuration,
		iconsPathUrl: new URL( configuration.iconsPathUrl ?? '' ),
	};
}
