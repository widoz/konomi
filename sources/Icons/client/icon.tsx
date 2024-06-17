/**
 * External dependencies
 */
import type { JSX } from 'react';
import React from 'react';
import { useConfiguration } from '@konomi/configuration';

type Properties = Readonly< { icon: string } >;

export function Render( props: Properties ): JSX.Element {
	const { iconsPathUrl } = useConfiguration();
	return (
		<img
			src={ `${ iconsPathUrl.toString() }/${ props.icon }.svg` }
			alt={ `Icon: ${ props.icon }` }
		/>
	);
}
