/**
 * External dependencies
 */
import { Icon } from '@konomi/icons';
import type { JSX } from 'react';
import React from 'react';
/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit(): JSX.Element {
	return (
		<button { ...useBlockProps() }>
			<Icon icon="heart" />
		</button>
	);
}
