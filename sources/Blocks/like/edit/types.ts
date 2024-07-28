/**
 * External dependencies
 */
import type React from 'react';

export namespace LikeEdit {
	// TODO Change type to recognize HEX
	export type Color = string | undefined;

	export type Attributes = Readonly< {
		inactiveColor: Color;
		activeColor: Color;
	} >;

	export type EditProps = Readonly< {
		attributes: Attributes;
		setAttributes: ( newAttributes: Partial< Attributes > ) => void;
	} >;

	// TODO Rename to a --konomi-color--(inactive|active)
	export interface CustomCSSProperties extends React.CSSProperties {
		'--konomi-color--gray': Attributes[ 'inactiveColor' ];
		'--konomi-color--crimson': Attributes[ 'activeColor' ];
	}
}
