import { jest, describe, it, expect, beforeEach } from '@jest/globals';
import { render } from '@testing-library/react';
import React from 'react';
import { BlockComponent } from '../../../../../../sources/Blocks/Like/edit/block-component';
import { fromPartial } from '@total-typescript/shoehorn';

import type { LikeEdit } from '../../../../../../sources/Blocks/Like/edit/types';

jest.mock( '@wordpress/block-editor', () => ( {
	useBlockProps: ( ...props ) => ( {
		className: 'mock-block-props',
		...props,
	} ),
} ) );

jest.mock( '@konomi/icons', () => ( {
	SvgHeart: () => <div data-testid="svg-heart">Heart Icon</div>,
} ) );

describe( 'BlockComponent', () => {
	beforeEach( () => {
		jest.clearAllMocks();
	} );

	it( 'should match snapshot', () => {
		const props = fromPartial< LikeEdit.EditProps >( {
			attributes: {
				inactiveColor: '#cccccc',
				activeColor: '#ff0000',
			},
		} );

		const { container } = render( <BlockComponent { ...props } /> );

		expect( container ).toMatchSnapshot();
	} );
} );
