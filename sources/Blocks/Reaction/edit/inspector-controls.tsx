import type { JSX } from 'react';
import React from 'react';
import { ColorSettings } from './color-settings';
import type { ReactionEdit } from './types';

export function InspectorControls(
	props: ReactionEdit.EditProps
): JSX.Element {
	return <ColorSettings { ...props } />;
}
