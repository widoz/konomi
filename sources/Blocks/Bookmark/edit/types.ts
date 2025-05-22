/**
 * External dependencies
 */
import type React from 'react';

export namespace BookmarkEdit {
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
		'--konomi-color--inactive': Attributes[ 'inactiveColor' ];
		'--konomi-color--active': Attributes[ 'activeColor' ];
	}
}
