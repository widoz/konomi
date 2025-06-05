/**
 * External dependencies
 */
import { SvgHeart } from '@konomi/icons';
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
import type { ReactionEdit } from './types';

export function BlockComponent(
	props: Readonly< ReactionEdit.EditProps >
): JSX.Element {
	const { inactiveColor, activeColor } = props.attributes;

	const style: ReactionEdit.CustomCSSProperties = {
		'--konomi-color--inactive': inactiveColor,
		'--konomi-color--active': activeColor,
	};

	return (
		<div className="konomi-reaction" style={ style }>
			<button { ...useBlockProps() }>
				<SvgHeart />
				<span className="konomi-count">10</span>
			</button>
		</div>
	);
}
