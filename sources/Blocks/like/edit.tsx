import React from 'react';

import { __ } from '@wordpress/i18n';

import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

export default function Edit(): JSX.Element {
	return (
		<p { ...useBlockProps() }>
			{ __( 'Like – hello from the editor!', 'like' ) }
		</p>
	);
}
