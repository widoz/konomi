import { findInteractivityParent } from '../utils';

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
