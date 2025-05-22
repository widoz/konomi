import {
	jest,
	describe,
	it,
	expect,
	beforeEach,
} from '@jest/globals';
import { parseHTML } from '@test/helpers';
import { renderMessage } from '../../../../../../sources/Blocks/Konomi/view/popover';
import { popoverElement } from '../../../../../../sources/Blocks/Konomi/view/elements/popover-element';

jest.mock(
	'../../../../../../sources/Blocks/Konomi/view/elements/popover-element',
	() => ( {
		popoverElement: jest.fn(),
	} )
);

describe( 'Popover Module', () => {
	const MARKUP_WITH_ERROR_DATA = `
    <div data-wp-interactive="konomi">
      <div class="konomi-like-response-message">Popover Message</div>
      <button class="trigger-button" data-error="Test error message">Trigger</button>
    </div>
  `;

	beforeEach( () => {
		jest.clearAllMocks();
	} );

	describe( 'renderMessage', () => {
		it( 'should render the popover and hide it after delay', ( done ) => {
			const parent = parseHTML( MARKUP_WITH_ERROR_DATA );
			const toggler = parent.querySelector(
				'.trigger-button'
			) as HTMLElement;

			const popover = {
				innerHTML: '',
				showPopover: jest.fn(),
				hidePopover: jest.fn(),
			};

			jest.mocked( popoverElement ).mockReturnValue(
				popover as unknown as HTMLElement
			);

			renderMessage( toggler ).finally( () => {
				expect( popoverElement ).toHaveBeenCalledWith( toggler );
				expect( popover.showPopover ).toHaveBeenCalled();
				expect( popover.hidePopover ).toHaveBeenCalled();
				done();
			} );
		} );

		it( 'should reject when popover element is not found', async () => {
			const parent = parseHTML( MARKUP_WITH_ERROR_DATA );
			const toggler = parent.querySelector(
				'.trigger-button'
			) as HTMLElement;

			jest.mocked( popoverElement ).mockReturnValue(
				null as unknown as HTMLElement
			);

			await expect( renderMessage( toggler ) ).rejects.toThrow(
				'Konomi: Popover element not found.'
			);
		} );
	} );
} );
