/**
 * WordPress dependencies
 */
// @ts-expect-error
import { registerBlockType } from '@wordpress/blocks';

import { Edit } from './edit';

registerBlockType( 'konomi/like', {
	edit: Edit,
} );
