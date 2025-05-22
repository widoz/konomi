import React from 'react';
import { jest, describe, it, expect, beforeEach } from '@jest/globals';
import { render } from '@testing-library/react';
import { fromPartial } from '@total-typescript/shoehorn';
import { BlockComponent } from '../../../../../../sources/Blocks/Bookmark/edit/block-component';

import type { BookmarkEdit } from '../../../../../../sources/Blocks/Bookmark/edit/types';

jest.mock( '@wordpress/block-editor', () => ( {
	useBlockProps: ( ...props ) => ( {
		className: 'mock-block-props',
		...props,
	} ),
} ) );

jest.mock( '@konomi/icons', () => ( {
	SvgBookmark: () => <div data-testid="svg-bookmark">Bookmark Icon</div>,
} ) );

describe( 'BlockComponent', () => {
	beforeEach( () => {
		jest.clearAllMocks();
	} );

	it( 'should match snapshot', () => {
		const props = fromPartial< BookmarkEdit.EditProps >( {
			attributes: {
				inactiveColor: '#cccccc',
				activeColor: '#ff0000',
			},
		} );

		const { container } = render( <BlockComponent { ...props } /> );

		expect( container ).toMatchSnapshot();
	} );
} );
