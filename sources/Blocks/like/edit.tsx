import React, {JSX} from 'react'

import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

export default function Edit(props): JSX.Element {
	return (
	  <div {...useBlockProps()}>
		  <img src={`${props.configuration?.iconsPathUrl}/suit-heart.svg`} alt="Like" />
	  </div>
	);
}
