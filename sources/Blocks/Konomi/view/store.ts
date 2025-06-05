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
import { loginModalElement } from './elements/login-modal-element';
import { renderMessage } from './popover';

export type ResponseError = Readonly< {
	code: string;
	message: string;
	data: {
		status: number;
	};
} >;

export type Context = {
	id: number;
	type: string;
	isUserLoggedIn: boolean;
	loginRequired: boolean;
	error: {
		code: ResponseError[ 'code' ];
		message: ResponseError[ 'message' ];
	};
};

// eslint-disable-next-line max-lines-per-function
export function init(): void {
	store( 'konomi', {
		state: {},

		actions: {
			closeLoginModal: () => {
				const context = getContext< Context >( 'konomi' );
				context.loginRequired = false;
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
