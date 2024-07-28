/**
 * External dependencies
 */
/**
 * External dependencies
 */
/**
 * External dependencies
 */
/**
 * External dependencies
 */
/**
 * External dependencies
 */
/**
 * External dependencies
 */
import { SvgHeart } from '@konomi/icons';
import type { JSX } from 'react';
import React from 'react';

/**
 * WordPress dependencies
 */
/**
 * WordPress dependencies
 */
/**
 * WordPress dependencies
 */
/**
 * WordPress dependencies
 */
/**
 * WordPress dependencies
 */
/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
/**
 * Internal dependencies
 */
/**
 * Internal dependencies
 */
/**
 * Internal dependencies
 */
/**
 * Internal dependencies
 */
/**
 * Internal dependencies
 */
import type { LikeEdit } from './types';

export function BlockComponent(
	props: Readonly< LikeEdit.EditProps >
): JSX.Element {
	const { inactiveColor, activeColor } = props.attributes;

	const style: LikeEdit.CustomCSSProperties = {
		'--konomi-color--gray': inactiveColor,
		'--konomi-color--crimson': activeColor,
	};

	return (
		<div className="konomi-like" style={ style }>
			<button { ...useBlockProps() }>
				<SvgHeart />
			</button>
		</div>
	);
}
