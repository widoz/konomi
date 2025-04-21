/**
 * Internal dependencies
 */
import { popoverElement } from './elements/popover-element';

export function renderMessage(
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
