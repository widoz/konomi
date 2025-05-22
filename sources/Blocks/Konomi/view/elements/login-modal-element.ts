import { findInteractivityParent } from '../utils';

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

function assertDialogHTMLElement(
	element: unknown
): asserts element is HTMLDialogElement {
	if ( element === null || element === undefined ) {
		throw new Error( 'Element is not an HTMLDialogElement' );
	}
}
