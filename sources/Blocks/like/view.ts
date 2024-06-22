import { getContext, store } from '@wordpress/interactivity';

type Context = {
	entityId: number;
	entityType: string;
	userId: number;
	isActive: boolean;
};

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
			const {
				entityId,
				entityType,
				isActive,
				userId: id,
			} = getContext< Context >( 'konomi' );

			fetch( {
				path: '/wp/v2/users',
				method: 'POST',
				data: {
					id,
					meta: {
						_likes: { entityId, entityType },
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
