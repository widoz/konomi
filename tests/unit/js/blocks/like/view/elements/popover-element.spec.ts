import { jest, describe, it, expect, beforeEach } from '@jest/globals';
import { parseHTML } from '@test/helpers';
import { findInteractivityParent } from '../../../../../../../sources/Blocks/Like/view/utils';
import {
	popoverElement,
} from '../../../../../../../sources/Blocks/Like/view/elements/popover-element';

jest.mock( '../../../../../../../sources/Blocks/Like/view/utils', () => ({
	findInteractivityParent: jest.fn(),
}) );

describe( 'popoverElement', () => {
	const VALID_POPOVER_MARKUP = `
    <div data-wp-interactive="konomi">
      <div class="konomi-like-response-message">Popover Message</div>
      <button class="trigger-button">Trigger</button>
    </div>
  `;

	const EMPTY_MARKUP = `
    <div data-wp-interactive="konomi">
      <button class="trigger-button">Trigger</button>
    </div>
  `;

	beforeEach( () => {
		jest.clearAllMocks();
	} );

	it( 'should return the popover element when it exists', () => {
		const parent = parseHTML( VALID_POPOVER_MARKUP );
		const testElement = parent.querySelector( '.trigger-button' ) as HTMLElement;
		const popover = parent.querySelector( '.konomi-like-response-message' ) as HTMLElement;
		jest.mocked( findInteractivityParent ).mockReturnValue( parent );
		const result = popoverElement( testElement );
		expect( result ).toBe( popover );
	} );

	it( 'should throw an error when popover element is not found', () => {
		const parent = parseHTML( EMPTY_MARKUP );
		const testElement = parent.querySelector( '.trigger-button' ) as HTMLElement;
		jest.mocked( findInteractivityParent ).mockReturnValue( parent );
		expect( () => popoverElement( testElement ) ).toThrow( 'Element is null' );
	} );

	it( 'should throw an error when parent is not found', () => {
		const parent = parseHTML( VALID_POPOVER_MARKUP );
		const testElement = parent.querySelector( '.trigger-button' ) as HTMLElement;
		jest.mocked( findInteractivityParent ).mockReturnValue( null );
		expect( () => popoverElement( testElement ) ).toThrow( 'Element is null' );
	} );
} );
