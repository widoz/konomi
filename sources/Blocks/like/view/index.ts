import { getContext, getElement, store } from '@wordpress/interactivity';
import { addLike } from './add-like-command';
import { loginModalElement } from './elements';
import { renderResponseError } from './popover';

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
};

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

		*updateUserPreferences(): Generator< Promise< void > > {
			const context = getContext< Context >( 'konomi' );

			if ( ! context.isUserLoggedIn ) {
				context.loginRequired = true;
				actions.revertStatus();
				return;
			}

			const element = getElement();

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

				actions.revertStatus();
				if ( element.ref ) {
					element.ref.dataset[ 'error' ] = responseError.message;
				}
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
		maybeShowErrorPopup: (): void => {
			const element = getElement();
			if ( ! ( element.ref instanceof HTMLElement ) ) {
				return;
			}

			renderResponseError( element.ref );
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
