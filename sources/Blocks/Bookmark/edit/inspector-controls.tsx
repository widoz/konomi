import type { JSX } from 'react';
import React from 'react';
import { ColorSettings } from './color-settings';
import type { BookmarkEdit } from './types';

export function InspectorControls(
	props: BookmarkEdit.EditProps
): JSX.Element {
	return <ColorSettings { ...props } />;
}
