/**
 * WordPress dependencies
 */
// @ts-expect-error
import { registerBlockType } from '@wordpress/blocks';

import { Edit } from './edit';
import { Save } from './edit/save';

registerBlockType( 'konomi/konomi', {
	edit: Edit,
	save: Save,
} );
