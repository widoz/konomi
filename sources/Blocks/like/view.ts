import { getContext, getElement, store } from '@wordpress/interactivity';

declare global {
	interface Window {
		wp: {
			apiFetch: ( options: any ) => Promise< any >;
		};
	}
}

type Context = {
	id: number;
	type: string;
	isActive: boolean;
};

const { apiFetch } = window.wp;

const { state, callbacks } = store( 'konomi', {
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
			const popover = element.ref?.previousElementSibling;
			// @ts-expect-error
			const message = element.ref?.dataset.error ?? '';

			if (
				message &&
				popover instanceof HTMLElement &&
				popover.classList.contains( 'konomi-like-response-message' )
			) {
				popover.innerHTML = message;
				popover.showPopover();

				setTimeout( () => {
					popover.hidePopover();
				}, 3000 );
			}
		},

		updateUserPreferences: () => {
			const element = getElement();
			const context = getContext< Context >( 'konomi' );

			apiFetch( {
				path: '/konomi/v1/user-like/',
				method: 'POST',
				data: {
					meta: {
						_like: {
							id: context.id,
							type: context.type,
							isActive: context.isActive,
						},
					},
				},
			} ).catch( ( error ) => {
				console.error( error );
				context.isActive = false;
				// @ts-expect-error
				element.ref.dataset.error = error.message;
			} );
		},
	},
} );
