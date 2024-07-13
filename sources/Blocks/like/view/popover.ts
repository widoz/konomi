// eslint-disable-next-line complexity
export function showResponseWithPopover(
	// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
	popover: HTMLElement,
	message: string
): void {
	if ( ! message ) {
		return;
	}
	if ( ! popover.classList.contains( 'konomi-like-response-message' ) ) {
		return;
	}

	popover.innerHTML = message;
	popover.showPopover();

	setTimeout( () => {
		popover.hidePopover();
	}, 3000 );
}
