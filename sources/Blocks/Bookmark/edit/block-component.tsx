/**
 * External dependencies
 */
import { SvgBookmark } from '@konomi/icons';
import type { JSX } from 'react';
import React from 'react';

/**
 * WordPress dependencies
 */
// @ts-expect-error
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import type { BookmarkEdit } from './types';

export function BlockComponent(
	props: Readonly< BookmarkEdit.EditProps >
): JSX.Element {
	const { inactiveColor, activeColor } = props.attributes;

	const style: BookmarkEdit.CustomCSSProperties = {
		'--konomi-color--inactive': inactiveColor,
		'--konomi-color--active': activeColor,
	};

	return (
		<div className="konomi-bookmark" style={ style }>
			<button { ...useBlockProps() }>
				<SvgBookmark />
			</button>
		</div>
	);
}
