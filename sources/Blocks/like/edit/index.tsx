/**
 * External dependencies
 */
import type { JSX } from 'react';
import React from 'react';

/**
 * Internal dependencies
 */
import type { LikeEdit } from './types';
import { BlockComponent } from './block-component';
import { InspectorControls } from './inspector-controls';

export function Edit( props: Readonly< LikeEdit.EditProps > ): JSX.Element {
	return (
		<>
			<BlockComponent { ...props } />
			<InspectorControls { ...props } />
		</>
	);
}
