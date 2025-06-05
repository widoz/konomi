/**
 * External dependencies
 */
import type { JSX } from 'react';
import React from 'react';

/**
 * WordPress dependencies
 */
// @ts-expect-error
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

/**
 * Internal Dependencies
 */
import type { KonomiEdit } from './types';

export function Edit( props: KonomiEdit.EditProps ): JSX.Element {
	const { attributes } = props;
	const blockProps = useBlockProps();
	const innerBlockProps = useInnerBlocksProps( blockProps, {
		template: [
			[
				'core/group',
				{
					layout: {
						type: 'flex',
						flexWrap: 'nowrap',
					},
				},
				[
					[ 'konomi/reaction', {} ],
					[ 'konomi/bookmark', {} ],
				],
			],
		],
		templateLock: 'insert',
		allowedBlocks: attributes.allowedBlocks,
	} );

	return <div { ...innerBlockProps } />;
}
