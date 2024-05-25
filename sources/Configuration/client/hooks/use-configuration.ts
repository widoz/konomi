/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
/**
 * External dependencies
 */
import type Konomi from '@konomi/types';

// TODO Add a parse args.
// TODO Introduce Zod Lib.
export function useConfiguration(): Konomi.Configuration {
	const defaults = {
		iconsPathUrl: new URL( '' ),
	};

	const configuration: Partial< Konomi.Configuration > = useSelect(
		( select ) => select( 'core' ).getSite().konomi ?? defaults
	);

	return {
		...defaults,
		...configuration,
		iconsPathUrl: new URL( configuration.iconsPathUrl ?? '' ),
	};
}
