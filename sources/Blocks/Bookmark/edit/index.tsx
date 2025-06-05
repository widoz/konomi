/**
 * External dependencies
 */
import type { JSX } from 'react';
import React from 'react';

/**
 * Internal dependencies
 */
import type { BookmarkEdit } from './types';
import { BlockComponent } from './block-component';
import { InspectorControls } from './inspector-controls';

export function Edit( props: Readonly< BookmarkEdit.EditProps > ): JSX.Element {
	return (
		<>
			<BlockComponent { ...props } />
			<InspectorControls { ...props } />
		</>
	);
}
