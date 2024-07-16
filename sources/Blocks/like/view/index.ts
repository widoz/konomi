import { getContext, getElement, store } from '@wordpress/interactivity';
import { addLike } from './add-like-command';
import { showResponseErrorWithPopoverForElement } from './popover';

type Context = {
	id: number;
	type: string;
	isActive: boolean;
	count: number;
};

store( 'konomi', {
	state: {},

	actions: {
		toggleStatus: (): void => {
			const context = getContext< Context >( 'konomi' );
			context.isActive = ! context.isActive;
			context.count = context.isActive
				? context.count + 1
				: context.count - 1;
		},

		updateUserPreferences: (): void => {
			const element = getElement();
			const context = getContext< Context >( 'konomi' );

			addLike( {
				meta: {
					_like: {
						id: context.id,
						type: context.type,
						isActive: context.isActive,
					},
				},
			} ).catch( ( error: Readonly< Error > ) => {
				context.count = context.isActive
					? context.count - 1
					: context.count + 1;
				// TODO Move this context change into the action. Check the handbook.
				context.isActive = ! context.isActive;
				if ( element.ref ) {
					element.ref.dataset[ 'error' ] = error.message;
				}
			} );
		},
	},

	callbacks: {
		updateLikeCount: (): void => {
			const element = getElement();
			if ( ! ( element.ref instanceof HTMLElement ) ) {
				return;
			}

			const parentElement = element.ref.parentElement;
			const likeCountElement =
				parentElement?.querySelector( '.konomi-like-count' );
			if ( ! ( likeCountElement instanceof HTMLElement ) ) {
				return;
			}

			const context = getContext< Context >( 'konomi' );
			// TODO Change `count`  to something else, also in HTML, I don't like this term.
			likeCountElement.textContent = String( context.count );
		},

		maybeShowErrorPopup: (): void => {
			const element = getElement();
			if ( element.ref instanceof HTMLElement ) {
				showResponseErrorWithPopoverForElement( element.ref );
			}
		},
	},
} );
