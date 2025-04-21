/**
 * Internal dependencies
 */
import { popoverElement } from './elements/popover-element';

const findPopover = (
	// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
	toggler: Readonly< HTMLElement >
): Promise< Readonly< HTMLElement > > => {
	const element = popoverElement( toggler );
	return element
		? Promise.resolve( element )
		: Promise.reject( new Error( 'Konomi: Popover element not found.' ) );
};

export function renderMessage(
	// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
	toggler: Readonly< HTMLElement >
): Promise< void > {
	return findPopover( toggler ).then(
		// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
		( popover ) => {
			popover.showPopover();

			return new Promise< void >( ( resolve ) => {
				setTimeout( () => {
					popover.hidePopover();
					resolve();
				}, 3000 );
			} );
		}
	);
}
