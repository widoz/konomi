/**
 * WordPress dependencies
 */
import { getContext, store } from '@wordpress/interactivity';

/**
 * Internal dependencies
 */
import { addLike } from './add-like-command';
import type {
	Context as OuterContext,
	ResponseError,
} from '../../Konomi/view/store';

export type Context = {
	isActive: boolean;
	count: number;
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
				const outerContext = getContext< OuterContext >( 'konomi' );

				if ( ! outerContext.isUserLoggedIn ) {
					outerContext.loginRequired = true;
					actions.revertStatus();
					return;
				}

				try {
					const likeContext = getContext< Context >( 'konomiLike' );
					yield addLike( {
						meta: {
							_reaction: {
								id: outerContext.id,
								type: outerContext.type,
								isActive: likeContext.isActive,
							},
						},
					} );
				} catch ( error: any ) {
					const responseError = error as ResponseError;
					outerContext.error = {
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
