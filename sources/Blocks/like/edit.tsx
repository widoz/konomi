/**
 * External dependencies
 */
import type { JSX } from 'react';
import React from 'react';
/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
/**
 * External dependencies
 */
import { useConfiguration } from '@konomi/configuration';

export default function Edit(): JSX.Element {
	const { iconsPathUrl } = useConfiguration();

	return (
		<button { ...useBlockProps() }>
			<img
				src={ `${ iconsPathUrl.toString() }/suit-heart.svg` }
				alt="Like"
			/>
		</button>
	);
}
