import {
	InspectorControls as _InspectControls,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalPanelColorGradientSettings as PanelColorGradientSettings,
	// @ts-expect-error
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import type { JSX } from 'react';
import React from 'react';
import type { ReactionEdit } from './types';

type ColorSetting = Readonly< {
	label: string;
	colorValue: ReactionEdit.Color;
	onColorChange: ( color: ReactionEdit.Color ) => void;
	onColorCleared: ( color: ReactionEdit.Color ) => void;
} >;

export function ColorSettings( props: ReactionEdit.EditProps ): JSX.Element {
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
		[ 'inactiveColor' | 'activeColor', ReactionEdit.Color ]
	>,
	onChangeColor: ReactionEdit.EditProps[ 'setAttributes' ]
): ColorSetting {
	return {
		label,
		colorValue: attribute[ 1 ],
		onColorChange: ( color: ReactionEdit.Color ): void => {
			onChangeColor( { [ attribute[ 0 ] ]: color } );
		},
		onColorCleared: (): void => {
			onChangeColor( { [ attribute[ 0 ] ]: undefined } );
		},
	};
}
