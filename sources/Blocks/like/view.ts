import { getContext, store } from '@wordpress/interactivity';

type Context = {
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
} );
