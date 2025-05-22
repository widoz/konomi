import { jest, describe, it, expect, beforeEach } from '@jest/globals';
import { parseHTML } from '@test/helpers';
import { findInteractivityParent } from '../../../../../../../sources/Blocks/Konomi/view/utils';
import { popoverElement } from '../../../../../../../sources/Blocks/Konomi/view/elements/popover-element';

jest.mock( '../../../../../../../sources/Blocks/Konomi/view/utils', () => ( {
	findInteractivityParent: jest.fn(),
} ) );

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
		const testElement = parent.querySelector(
			'.trigger-button'
		) as HTMLElement;
		const popover = parent.querySelector(
			'.konomi-response-message'
		) as HTMLElement;
		jest.mocked( findInteractivityParent ).mockReturnValue( parent );
		const result = popoverElement( testElement );
		expect( result ).toBe( popover );
	} );

	it( 'should return null when the popover element does not exist', () => {
		const parent = parseHTML( EMPTY_MARKUP );
		const testElement = parent.querySelector(
			'.trigger-button'
		) as HTMLElement;
		jest.mocked( findInteractivityParent ).mockReturnValue( parent );
		const result = popoverElement( testElement );
		expect( result ).toBeNull();
	} );
} );
