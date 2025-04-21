/**
 * Internal dependencies
 */
import { popoverElement } from './elements/popover-element';

export function renderMessage(
	// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
	toggler: Readonly< HTMLElement >,
	onHideMessage: () => void
): void {
	const popover = popoverElement( toggler );
	popover.showPopover();

	setTimeout( () => {
		popover.hidePopover();
		onHideMessage();
	}, 3000 );
}
