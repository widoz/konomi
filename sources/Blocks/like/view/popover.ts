import { popoverElement } from './elements';

// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
export function renderResponseError( toggler: HTMLElement ): void {
	renderResponse( toggler, 'error' );
}

// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
function renderResponse( toggler: HTMLElement, key: string ): void {
	const message = toggler.dataset[ key ] ?? '';
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
