import {
	jest,
	describe,
	it,
	expect,
	beforeEach,
	afterEach,
} from '@jest/globals';
import { parseHTML } from '@test/helpers';
import { renderMessage } from '../../../../../../sources/Blocks/Like/view/popover';
import { popoverElement } from '../../../../../../sources/Blocks/Like/view/elements/popover-element';

jest.mock(
	'../../../../../../sources/Blocks/Like/view/elements/popover-element',
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

	const MARKUP_WITH_EMPTY_ERROR_DATA = `
    <div data-wp-interactive="konomi">
      <div class="konomi-like-response-message">Popover Message</div>
      <button class="trigger-button" data-error="">Trigger</button>
    </div>
  `;

	const MARKUP_WITHOUT_ERROR_DATA = `
    <div data-wp-interactive="konomi">
      <div class="konomi-like-response-message">Popover Message</div>
      <button class="trigger-button">Trigger</button>
    </div>
  `;

	beforeEach( () => {
		jest.useFakeTimers();
		jest.clearAllMocks();
	} );

	afterEach( () => {
		jest.useRealTimers();
	} );

	describe( 'renderMessage', () => {
		it( 'render the popover and execute a callback when it becomes hidden', () => {
			const parent = parseHTML( MARKUP_WITH_ERROR_DATA );
			const toggler = parent.querySelector(
				'.trigger-button'
			) as HTMLElement;

			const popover = {
				innerHTML: '',
				showPopover: jest.fn(),
				hidePopover: jest.fn(),
			};
			const onHideMessage = jest.fn();

			jest.mocked( popoverElement ).mockReturnValue(
				popover as unknown as HTMLElement
			);

			renderMessage( toggler, onHideMessage );

			expect( popoverElement ).toHaveBeenCalledWith( toggler );
			expect( popover.showPopover ).toHaveBeenCalled();

			jest.advanceTimersByTime( 3000 );

			expect( popover.hidePopover ).toHaveBeenCalled();
			expect( onHideMessage ).toHaveBeenCalled();
		} );
	} );
} );
