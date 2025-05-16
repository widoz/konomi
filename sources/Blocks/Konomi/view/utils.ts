export function findInteractivityParent(
	// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
	element: Readonly< HTMLElement > | null
): Readonly< HTMLElement > | null {
	// TODO I don't know if I like to have the namespace harcoded here.
	while ( element && element.dataset[ 'wpInteractive' ] !== 'konomi' ) {
		element = element.parentElement;
	}
	return element;
}
