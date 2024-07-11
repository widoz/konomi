/**
 * External dependencies
 */
import type { JSX } from 'react';
import React from 'react';
import { useConfiguration } from '@konomi/configuration';

type Properties = Readonly< { icon: string } >;

export function Icon( props: Properties ): JSX.Element {
	const { iconsPathUrl } = useConfiguration();
	return (
		<img
			className={ `konomi-icon konomi-icon--${ props.icon }` }
			src={ `${ iconsPathUrl.toString() }/${ props.icon }.svg` }
			alt={ `Icon: ${ props.icon }` }
		/>
	);
}
