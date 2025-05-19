/**
 * WordPress dependencies
 */
import { getContext, store } from '@wordpress/interactivity';

/**
 * Internal dependencies
 */
import { addBookmark } from './add-bookmark-command';
import type {
	Context as OuterContext,
	ResponseError,
} from '../../Konomi/view/store';

type Context = {
	isActive: boolean;
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
				const outerContext = getContext< OuterContext >( 'konomi' );

				if ( ! outerContext.isUserLoggedIn ) {
					outerContext.loginRequired = true;
					actions.revertStatus();
					return;
				}

				try {
					const context = getContext< Context >( 'konomiBookmark' );
					yield addBookmark( {
						meta: {
							_bookmark: {
								id: outerContext.id,
								type: outerContext.type,
								isActive: context.isActive,
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
				const context = getContext< Context >( 'konomiBookmark' );
				context.isActive = ! context.isActive;
			},
		},
	} );
}
