/**
 * External dependencies
 */
import type { JSX } from 'react';
import React from 'react';
import { useConfiguration } from '@konomi/configuration';

export function Render( props: Readonly< { icon: string } > ): JSX.Element {
	const { iconsPathUrl } = useConfiguration();
	return (
		<img
			src={ `${ iconsPathUrl.toString() }/${ props.icon }.svg` }
			alt=""
		/>
	);
}
