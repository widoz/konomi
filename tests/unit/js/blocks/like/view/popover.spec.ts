import { jest, describe, it, expect, beforeEach, afterEach } from '@jest/globals';
import { parseHTML } from '@test/helpers';
import { renderResponseError } from '../../../../../../sources/Blocks/Like/view/popover';
import { popoverElement } from '../../../../../../sources/Blocks/Like/view/elements/popover-element';

jest.mock('../../../../../../sources/Blocks/Like/view/elements/popover-element', () => ({
	popoverElement: jest.fn(),
}));

describe('Popover Module', () => {
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

	beforeEach(() => {
		jest.useFakeTimers();
		jest.clearAllMocks();
	});

	afterEach(() => {
		jest.useRealTimers();
	});

	describe('renderResponseError', () => {
		it('should show popover with error message when error data attribute exists', () => {
			const parent = parseHTML(MARKUP_WITH_ERROR_DATA);
			const toggler = parent.querySelector('.trigger-button') as HTMLElement;

			const popover = {
				innerHTML: '',
				showPopover: jest.fn(),
				hidePopover: jest.fn(),
			};

			jest.mocked(popoverElement).mockReturnValue(popover as unknown as HTMLElement);

			renderResponseError(toggler, 'Default message');

			expect(popoverElement).toHaveBeenCalledWith(toggler);
			expect(popover.innerHTML).toBe('Test error message');
			expect(popover.showPopover).toHaveBeenCalled();

			jest.advanceTimersByTime(3000);

			expect(popover.hidePopover).toHaveBeenCalled();
			expect(toggler.dataset.error).toBe('');
		});

		it('should not show popover when error data attribute is empty', () => {
			const parent = parseHTML(MARKUP_WITH_EMPTY_ERROR_DATA);
			const toggler = parent.querySelector('.trigger-button') as HTMLElement;

			const popover = {
				innerHTML: '',
				showPopover: jest.fn(),
				hidePopover: jest.fn(),
			};

			jest.mocked(popoverElement).mockReturnValue(popover as unknown as HTMLElement);

			renderResponseError(toggler, 'Default message');

			expect(popoverElement).not.toHaveBeenCalled();
			expect(popover.showPopover).not.toHaveBeenCalled();
		});

		it('should not show popover when error data attribute is not present and no default message', () => {
			const parent = parseHTML(MARKUP_WITHOUT_ERROR_DATA);
			const toggler = parent.querySelector('.trigger-button') as HTMLElement;

			const popover = {
				innerHTML: '',
				showPopover: jest.fn(),
				hidePopover: jest.fn(),
			};

			jest.mocked(popoverElement).mockReturnValue(popover as unknown as HTMLElement);

			renderResponseError(toggler, '');

			expect(popoverElement).not.toHaveBeenCalled();
			expect(popover.showPopover).not.toHaveBeenCalled();
		});

		it('should show popover with default message when error data attribute is not present but default message is provided', () => {
			const parent = parseHTML(MARKUP_WITHOUT_ERROR_DATA);
			const toggler = parent.querySelector('.trigger-button') as HTMLElement;

			const popover = {
				innerHTML: '',
				showPopover: jest.fn(),
				hidePopover: jest.fn(),
			};

			jest.mocked(popoverElement).mockReturnValue(popover as unknown as HTMLElement);

			const defaultMessage = 'This is a default error message';
			renderResponseError(toggler, defaultMessage);

			expect(popoverElement).toHaveBeenCalledWith(toggler);
			expect(popover.innerHTML).toBe(defaultMessage);
			expect(popover.showPopover).toHaveBeenCalled();

			jest.advanceTimersByTime(3000);

			expect(popover.hidePopover).toHaveBeenCalled();
			expect(toggler.dataset.error).toBe('');
		});
	});
});
