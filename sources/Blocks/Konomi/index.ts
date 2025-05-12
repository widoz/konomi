/**
 * WordPress dependencies
 */
// @ts-expect-error
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import './view/style.scss';

import { Edit } from './edit';
import { Save } from './edit/save';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	save: Save,
} );
