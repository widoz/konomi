/**
 * WordPress dependencies
 */
import { getContext, store } from '@wordpress/interactivity';

/**
 * Internal dependencies
 */
import { addBookmark } from './add-bookmark-command';

type Context = {
	isActive: boolean;
};

type ResponseError = Readonly< {
	code: string;
	message: string;
	data: {
		status: number;
	};
} >;

type KonomiContext = {
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
	const { actions } = store( 'konomiBookmark', {
		state: {},

		actions: {
			toggleStatus: (): void => {
				const context = getContext< Context >( 'konomiBookmark' );
				context.isActive = ! context.isActive;
				actions.updateUserPreferences();
			},

			// eslint-disable-next-line max-lines-per-function,complexity
			*updateUserPreferences(): Generator< Promise< void > > {
				const konomiContext = getContext< KonomiContext >( 'konomi' );

				if ( ! konomiContext.isUserLoggedIn ) {
					konomiContext.loginRequired = true;
					actions.revertStatus();
					return;
				}

				try {
					const context = getContext< Context >( 'konomiBookmark' );
					yield addBookmark( {
						meta: {
							_bookmark: {
								id: konomiContext.id,
								type: konomiContext.type,
								isActive: context.isActive,
							},
						},
					} );
				} catch ( error: any ) {
					const responseError = error as ResponseError;
					konomiContext.error = {
						code: responseError.code,
						message: responseError.message,
					};
					actions.revertStatus();
				}
			},

			revertStatus: (): void => {
				const context = getContext< Context >( 'konomiBookmark' );
				context.isActive = ! context.isActive;
			},
		},
	} );
}
