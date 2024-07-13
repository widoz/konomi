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

// TODO Switch to a server side rendered block?
export default function Edit(): JSX.Element {
	return (
		<button { ...useBlockProps() }>
			<Icon icon="heart" />
		</button>
	);
}
