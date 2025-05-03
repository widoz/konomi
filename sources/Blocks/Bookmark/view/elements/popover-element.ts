import { findInteractivityParent } from '../utils';

export function popoverElement(
	// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
	element: Readonly< HTMLElement >
): HTMLElement | null {
	return (
		findInteractivityParent( element )?.querySelector< HTMLElement >(
			'.konomi-bookmark-response-message'
		) ?? null
	);
}
