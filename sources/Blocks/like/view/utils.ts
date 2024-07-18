export function findKonomiInteractivityParent(
	element: Readonly< HTMLElement > | null
): HTMLElement | null {
	while ( element && element.dataset[ 'wpInteractive' ] !== 'konomi' ) {
		element = element.parentElement;
	}
	return element;
}
