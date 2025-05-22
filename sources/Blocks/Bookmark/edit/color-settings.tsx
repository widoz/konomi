import {
	InspectorControls as _InspectControls,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalPanelColorGradientSettings as PanelColorGradientSettings,
	// @ts-expect-error
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import type { JSX } from 'react';
import React from 'react';
import type { BookmarkEdit } from './types';

type ColorSetting = Readonly< {
	label: string;
	colorValue: BookmarkEdit.Color;
	onColorChange: ( color: BookmarkEdit.Color ) => void;
	onColorCleared: ( color: BookmarkEdit.Color ) => void;
} >;

// TODO How can I configure the PanelColorGradientSettings to only accept HEX colors?
export function ColorSettings( props: BookmarkEdit.EditProps ): JSX.Element {
	const { inactiveColor, activeColor } = props.attributes;

	const items = [
		createColorSetting(
			__( 'Inactive', 'konomi' ),
			[ 'inactiveColor', inactiveColor ],
			props.setAttributes
		),
		createColorSetting(
			__( 'Active', 'konomi' ),
			[ 'activeColor', activeColor ],
			props.setAttributes
		),
	];

	return (
		<_InspectControls group="styles">
			<PanelColorGradientSettings settings={ items } />
		</_InspectControls>
	);
}

function createColorSetting(
	label: string,
	attribute: Readonly<
		[ 'inactiveColor' | 'activeColor', BookmarkEdit.Color ]
	>,
	onChangeColor: BookmarkEdit.EditProps[ 'setAttributes' ]
): ColorSetting {
	return {
		label,
		colorValue: attribute[ 1 ],
		onColorChange: ( color: BookmarkEdit.Color ): void => {
			onChangeColor( { [ attribute[ 0 ] ]: color } );
		},
		onColorCleared: (): void => {
			onChangeColor( { [ attribute[ 0 ] ]: undefined } );
		},
	};
}
