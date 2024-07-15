import { getContext, getElement, store } from '@wordpress/interactivity';
import { addLike } from './add-like-command';
import { showResponseErrorWithPopoverForElement } from './popover';

type Context = {
	id: number;
	type: string;
	isActive: boolean;
	count: number;
};

const { callbacks } = store( 'konomi', {
	state: {},

	actions: {
		toggleStatus: () => {
			const context = getContext< Context >( 'konomi' );
			context.isActive = ! context.isActive;
			callbacks.updateUserPreferences();
			callbacks.toggleLikeCount();
		},
	},

	callbacks: {
		toggleLikeCount: (): void => {
			const element = getElement();
			if ( ! ( element.ref instanceof HTMLElement ) ) {
				return;
			}

			const likeCountElement = element.ref.nextElementSibling;
			if ( ! ( likeCountElement instanceof HTMLElement ) ) {
				return;
			}

			const context = getContext< Context >( 'konomi' );
			// TODO Change `count`  to something else, also in HTML, I don't like this term.
			const count = Number( likeCountElement.textContent );
			likeCountElement.textContent = String(
				context.isActive ? count + 1 : count - 1
			);
		},

		maybeShowErrorPopup: (): void => {
			const element = getElement();
			if ( element.ref instanceof HTMLElement ) {
				showResponseErrorWithPopoverForElement( element.ref );
			}
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
				// TODO Move this context change into the action. Check the handbook.
				context.isActive = ! context.isActive;
				if ( element.ref ) {
					element.ref.dataset[ 'error' ] = error.message;
				}
			} );
		},
	},
} );
