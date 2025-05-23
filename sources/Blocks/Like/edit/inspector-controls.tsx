// @ts-expect-error
import { InspectorControls as _InspectControls } from '@wordpress/block-editor';
import type { JSX } from 'react';
import React from 'react';
import { ColorSettings } from './color-settings';
import type { LikeEdit } from './types';

export function InspectorControls( props: LikeEdit.EditProps ): JSX.Element {
	return (
		<_InspectControls group="styles">
			<ColorSettings { ...props } />
		</_InspectControls>
	);
}
