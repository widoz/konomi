import { getContext, getElement, store } from '@wordpress/interactivity';
import { addLike } from './add-like-command';
import { showResponseErrorWithPopoverForElement } from './popover';

type Context = {
	id: number;
	type: string;
	isActive: boolean;
	count: number;
	isUserLoggedIn: boolean;
	loginRequired: boolean;
};

interface KonomiRestError extends Error {
	data: { status: number };
}

const { actions } = store( 'konomi', {
	state: {},

	actions: {
		toggleStatus: (): void => {
			const context = getContext< Context >( 'konomi' );

			context.isActive = ! context.isActive;
			context.count = context.isActive
				? context.count + 1
				: context.count - 1;

			actions.updateUserPreferences();
		},

		updateUserPreferences: (): void => {
			const context = getContext< Context >( 'konomi' );

			if ( ! context.isUserLoggedIn ) {
				context.loginRequired = true;
				actions.revertStatus();
				return;
			}

			const element = getElement();
			addLike( {
				meta: {
					_like: {
						id: context.id,
						type: context.type,
						isActive: context.isActive,
					},
				},
			} ).catch( ( error: Readonly< KonomiRestError > ) => {
				actions.revertStatus();
				if ( element.ref ) {
					element.ref.dataset[ 'error' ] = error.message;
				}
			} );
		},

		closeLoginModal: () => {
			const context = getContext< Context >( 'konomi' );
			context.loginRequired = false;
		},

		revertStatus: (): void => {
			const context = getContext< Context >( 'konomi' );
			context.count = context.isActive
				? context.count - 1
				: context.count + 1;
			context.isActive = ! context.isActive;
		},
	},

	callbacks: {
		maybeShowErrorPopup: (): void => {
			const element = getElement();
			if ( ! ( element.ref instanceof HTMLElement ) ) {
				return;
			}

			showResponseErrorWithPopoverForElement( element.ref );
		},

		toggleLoginModal: (): void => {
			const element = getElement();
			if ( ! ( element.ref instanceof HTMLElement ) ) {
				return;
			}

			const context = getContext< Context >( 'konomi' );
			const _loginModalElement = loginModalElement( element.ref );
			context.loginRequired
				? _loginModalElement.showModal()
				: _loginModalElement.close();
		},
	},
} );

function assertDialogHTMLElement(
	element: unknown
): asserts element is HTMLDialogElement {
	if ( element === null ) {
		throw new Error( 'Element is null' );
	}
}

function loginModalElement(
	element: Readonly< HTMLElement >
): Readonly< HTMLDialogElement > {
	const modal = element.parentElement?.querySelector( '.konomi-login-modal' );
	assertDialogHTMLElement( modal );
	return modal;
}
