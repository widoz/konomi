import { getContext, store } from '@wordpress/interactivity';

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
		updateUserPreferences: () => {
			const { isActive, id, type } = getContext< Context >( 'konomi' );

			apiFetch( {
				path: '/konomi/v1/user-like/',
				method: 'POST',
				data: {
					meta: {
						_likes: { id, type, isActive },
					},
				},
			} ).catch( ( error ) => {
				// TODO Here we want to render a popover with the error message.
				console.error( error );
				isActive = false;
			} );
		},
	},
} );
