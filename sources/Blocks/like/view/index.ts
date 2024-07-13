import { getContext, getElement, store } from '@wordpress/interactivity';
import { addLike } from './add-like-command';
import { showResponseErrorWithPopoverForElement } from './popover';

type Context = {
	id: number;
	type: string;
	isActive: boolean;
};

const { callbacks } = store( 'konomi', {
	state: {},

	actions: {
		toggleStatus: () => {
			const context = getContext< Context >( 'konomi' );
			context.isActive = ! context.isActive;
			callbacks.updateUserPreferences();
		},
	},

	callbacks: {
		maybeShowErrorPopup: () => {
			const element = getElement();
			if ( element.ref instanceof HTMLElement ) {
				showResponseErrorWithPopoverForElement( element.ref );
			}
		},

		updateUserPreferences: () => {
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
				context.isActive = false;
				if ( element.ref ) {
					element.ref.dataset[ 'error' ] = error.message;
				}
			} );
		},
	},
} );
