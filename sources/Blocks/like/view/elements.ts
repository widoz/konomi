import { findKonomiInteractivityParent } from './utils';

export function loginModalElement(
	element: Readonly< HTMLElement >
): Readonly< HTMLDialogElement > {
	const modal = findKonomiInteractivityParent( element )?.querySelector(
		'.konomi-login-modal'
	);
	assertDialogHTMLElement( modal );
	return modal;
}

export function popoverElement(
	element: Readonly< HTMLElement >
): HTMLElement {
	const popover = findKonomiInteractivityParent(
		element
	)?.querySelector< HTMLElement >( '.konomi-like-response-message' );
	assertHTMLElement( popover );
	return popover;
}

function assertHTMLElement( element: unknown ): asserts element is HTMLElement {
	if ( ! ( element instanceof HTMLElement ) ) {
		throw new Error( 'Element is null' );
	}
}

function assertDialogHTMLElement(
	element: unknown
): asserts element is HTMLDialogElement {
	if ( element === null ) {
		throw new Error( 'Element is null' );
	}
}
