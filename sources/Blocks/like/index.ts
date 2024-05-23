import {registerBlockType} from '@wordpress/blocks'

import './style.scss'

import Edit from './edit'
import metadata from './block.json'
import {withConfiguration} from '../components/with-configuration'

const ComposedEdit = withConfiguration(Edit)

registerBlockType(metadata.name, {
	edit: ComposedEdit,
})
