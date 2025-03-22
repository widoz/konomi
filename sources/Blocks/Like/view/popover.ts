/**
 * Internal dependencies
 */
import { popoverElement } from './elements/popover-element';

// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
export function renderResponseError( toggler: HTMLElement, defaultMessage: string ): void {
	renderResponse( toggler, 'error', defaultMessage );
}

// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
function renderResponse( toggler: HTMLElement, key: string, defaultMessage: string ): void {
	const message = toggler.dataset[ key ] ?? defaultMessage;
	if ( ! message ) {
		return;
	}

	const popover = popoverElement( toggler );
	popover.innerHTML = message;
	popover.showPopover();

	setTimeout( () => {
		popover.hidePopover();
		toggler.dataset[ key ] = '';
	}, 3000 );
}
