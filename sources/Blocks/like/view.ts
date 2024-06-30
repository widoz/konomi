import { getContext, store } from '@wordpress/interactivity';
import { useConfiguration } from '@konomi/configuration';

declare global {
	interface Window {
		wp: {
			apiFetch: ( options: any ) => Promise< any >;
		};
	}
}

type Context = {
	entityId: number;
	entityType: string;
	isActive: boolean;
};

const { apiFetch } = window.wp;

store( 'konomi', {
	state: {},
	actions: {
		toggleStatus: () => {
			const context = getContext< Context >( 'konomi' );
			context.isActive = ! context.isActive;
		},
	},

	callbacks: {
		updateUserPreferences: () => {
			const config = useConfiguration();

			const { entityId, entityType, isActive } =
				getContext< Context >( 'konomi' );

			apiFetch( {
				path: '/konomi/v1/user-like/',
				method: 'POST',
				data: {
					meta: {
						_likes: { entityId, entityType, isActive },
					},
				},
			} )
				.then( ( response ) => {
					console.log( response );
				} )
				.catch( ( error ) => {
					console.error( error );
					isActive = false;
				} );
		},
	},
} );
