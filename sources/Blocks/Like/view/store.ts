/**
 * WordPress dependencies
 */
import {
	getContext,
	getElement,
	useLayoutEffect,
	store,
} from '@wordpress/interactivity';

/**
 * Internal dependencies
 */
import { addLike } from './add-like-command';
import { loginModalElement } from './elements/login-modal-element';
import { renderMessage } from './popover';

type ResponseError = Readonly< {
	code: string;
	message: string;
	data: {
		status: number;
	};
} >;

type Context = {
	id: number;
	type: string;
	isActive: boolean;
	count: number;
	isUserLoggedIn: boolean;
	loginRequired: boolean;
	error: {
		code: ResponseError[ 'code' ];
		message: ResponseError[ 'message' ];
	};
};

// eslint-disable-next-line max-lines-per-function
export function init(): void {
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

			// eslint-disable-next-line max-lines-per-function,complexity
			*updateUserPreferences(): Generator< Promise< void > > {
				const context = getContext< Context >( 'konomi' );

				if ( ! context.isUserLoggedIn ) {
					context.loginRequired = true;
					actions.revertStatus();
					return;
				}

				try {
					yield addLike( {
						meta: {
							_like: {
								id: context.id,
								type: context.type,
								isActive: context.isActive,
							},
						},
					} );
				} catch ( error: any ) {
					const responseError = error as ResponseError;
					context.error = {
						code: responseError.code,
						message: responseError.message,
					};
					actions.revertStatus();
				}
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
			maybeRenderResponseError: (): void => {
				const context = getContext< Context >( 'konomi' );
				// eslint-disable-next-line react-hooks/rules-of-hooks
				useLayoutEffect( () => {
					const element = getElement();
					if ( element.ref && context.error.code ) {
						renderMessage( element.ref ).finally( () => {
							context.error = {
								code: '',
								message: '',
							};
						} );
					}
					// eslint-disable-next-line react-hooks/exhaustive-deps
				}, [ context.error.code, context.error.message ] );
			},

			toggleLoginModal: (): void => {
				const element = getElement();
				if ( ! ( element.ref instanceof HTMLElement ) ) {
					return;
				}

				const context = getContext< Context >( 'konomi' );
				const _loginModalElement = loginModalElement( element.ref );

				if ( context.loginRequired ) {
					_loginModalElement.showModal();
				} else {
					_loginModalElement.close();
				}
			},
		},
	} );
}
