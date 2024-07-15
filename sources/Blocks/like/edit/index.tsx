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

export function Edit(): JSX.Element {
	return (
		<div className="konomi-like">
			<button { ...useBlockProps() }>
				<Icon icon="heart" />
			</button>
		</div>
	);
}
