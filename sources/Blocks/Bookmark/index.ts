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

registerBlockType( 'konomi/bookmark', {
	edit: Edit,
} );
