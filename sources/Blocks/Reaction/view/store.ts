/**
 * WordPress dependencies
 */
import { getContext, store } from '@wordpress/interactivity';

/**
 * Internal dependencies
 */
import { addReaction } from './add-reaction-command';
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
	const { actions } = store( 'konomiReaction', {
		state: {},

		actions: {
			toggleStatus: (): void => {
				const context = getContext< Context >( 'konomiReaction' );

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
					const reactionContext =
						getContext< Context >( 'konomiReaction' );
					yield addReaction( {
						meta: {
							_reaction: {
								id: outerContext.id,
								type: outerContext.type,
								isActive: reactionContext.isActive,
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
				const context = getContext< Context >( 'konomiReaction' );
				context.count = context.isActive
					? context.count - 1
					: context.count + 1;
				context.isActive = ! context.isActive;
			},
		},
	} );
}
