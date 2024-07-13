// eslint-disable-next-line complexity
export function showResponseErrorWithPopoverForElement(
	// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
	toggler: Readonly< HTMLElement >
): void {
	const popover = toggler.previousElementSibling;
	const message = toggler.dataset[ 'error' ] ?? '';

	if (
		! ( popover instanceof HTMLElement ) ||
		! popover.classList.contains( 'konomi-like-response-message' )
	) {
		return;
	}

	if ( ! message ) {
		return;
	}

	popover.innerHTML = message;
	popover.showPopover();

	setTimeout( () => {
		popover.hidePopover();
		toggler.dataset[ 'error' ] = '';
	}, 3000 );
}
