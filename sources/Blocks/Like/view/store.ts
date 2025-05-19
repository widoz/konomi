/**
 * WordPress dependencies
 */
import { getContext, store } from '@wordpress/interactivity';

/**
 * Internal dependencies
 */
import { addLike } from './add-like-command';

type Context = {
	isActive: boolean;
	count: number;
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
	const { actions } = store( 'konomiLike', {
		state: {},

		actions: {
			toggleStatus: (): void => {
				const context = getContext< Context >( 'konomiLike' );

				context.isActive = ! context.isActive;
				context.count = context.isActive
					? context.count + 1
					: context.count - 1;

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
					const likeContext = getContext< Context >( 'konomiLike' );
					yield addLike( {
						meta: {
							_reaction: {
								id: konomiContext.id,
								type: konomiContext.type,
								isActive: likeContext.isActive,
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
				const context = getContext< Context >( 'konomiLike' );
				context.count = context.isActive
					? context.count - 1
					: context.count + 1;
				context.isActive = ! context.isActive;
			},
		},
	} );
}
