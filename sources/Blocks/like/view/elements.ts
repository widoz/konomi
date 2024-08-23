import { findInteractivityParent } from './utils';

export function loginModalElement(
	// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
	element: Readonly< HTMLElement >
): Readonly< HTMLDialogElement > {
	const modal = findInteractivityParent(
		element
	)?.querySelector< HTMLDialogElement >( '.konomi-login-modal' );
	assertDialogHTMLElement( modal );
	return modal;
}

export function popoverElement(
	// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
	element: Readonly< HTMLElement >
): HTMLElement {
	const popover = findInteractivityParent(
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
