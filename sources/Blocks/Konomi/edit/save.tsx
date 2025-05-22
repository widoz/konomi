import type { JSX } from 'react';
import React from 'react';

// @ts-expect-error
import { InnerBlocks } from '@wordpress/block-editor';

export function Save(): JSX.Element {
	return <InnerBlocks.Content />;
}
