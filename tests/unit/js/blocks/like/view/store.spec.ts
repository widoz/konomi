import { jest, describe, it, expect, beforeEach } from '@jest/globals';

import { getContext, store } from '@wordpress/interactivity';

import { init } from '../../../../../../sources/Blocks/Like/view/store';
import { addLike } from '../../../../../../sources/Blocks/Like/view/add-like-command';

jest.mock( '@wordpress/interactivity', () => ( {
	getContext: jest.fn(),
	getElement: jest.fn(),
	store: jest.fn(),
} ) );

jest.mock(
	'../../../../../../sources/Blocks/Like/view/add-like-command',
	() => ( {
		addLike: jest.fn(),
	} )
);

describe( 'Interactivity Store', () => {
	let mockNamespace: string;
	let mockStore: {
		state: Record< string, unknown >;
		actions: Record< string, ( ...args: any[] ) => void >;
		callbacks: Record< string, ( ...args: any[] ) => void >;
	};
	let mockContext: {
		id: number;
		type: string;
		isActive: boolean;
		count: number;
		isUserLoggedIn: boolean;
		loginRequired: boolean;
	};

	beforeEach( () => {
		jest.clearAllTimers();

		mockContext = {
			id: 1,
			type: 'post',
			isActive: false,
			count: 5,
			isUserLoggedIn: true,
			loginRequired: false,
		};
		jest.mocked( getContext ).mockReturnValue( mockContext );

		mockStore = { state: {}, actions: {}, callbacks: {} };
		jest.mocked( store ).mockImplementation(
			( namespace: string, config: any ) => {
				mockNamespace = namespace;
				mockStore = config;
				return { actions: config.actions };
			}
		);
	} );

	describe( 'store', () => {
		it( 'should be initialized with the correct namespace', () => {
			init();
			expect( mockNamespace ).toEqual( 'konomi' );
		} );
	} );

	describe( 'toggleStatus action', () => {
		it( 'should toggle isActive from false to true and increment count', () => {
			init();
			mockContext.isActive = false;
			mockContext.count = 5;
			mockStore.actions.toggleStatus();
			expect( mockContext.isActive ).toBe( true );
			expect( mockContext.count ).toBe( 6 );
		} );

		it( 'should toggle isActive from true to false and decrement count', () => {
			init();
			mockContext.isActive = true;
			mockContext.count = 5;
			mockStore.actions.toggleStatus();
			expect( mockContext.isActive ).toBe( false );
			expect( mockContext.count ).toBe( 4 );
		} );

		it( 'should call updateUserPreferences after toggling status', () => {
			init();
			const updateUserPreferencesSpy = jest.fn();
			mockStore.actions.updateUserPreferences = updateUserPreferencesSpy;
			mockStore.actions.toggleStatus();
			expect( updateUserPreferencesSpy ).toHaveBeenCalledTimes( 1 );
		} );
	} );

	describe( 'closeLoginModal action', () => {
		it( 'should set loginRequired to false', () => {
			init();
			mockContext.loginRequired = true;
			mockStore.actions.closeLoginModal();
			expect( mockContext.loginRequired ).toBe( false );
		} );
	} );

	describe( 'revertStatus action', () => {
		it( 'should revert isActive status from true to false and decrement count', () => {
			init();
			mockContext.isActive = true;
			mockContext.count = 6;
			mockStore.actions.revertStatus();
			expect( mockContext.isActive ).toBe( false );
			expect( mockContext.count ).toBe( 5 );
		} );

		it( 'should revert isActive status from false to true and increment count', () => {
			init();
			mockContext.isActive = false;
			mockContext.count = 5;
			mockStore.actions.revertStatus();
			expect( mockContext.isActive ).toBe( true );
			expect( mockContext.count ).toBe( 6 );
		} );
	} );

	describe( 'updateUserPreferences action', () => {
		it( 'should set loginRequired and revert status if user is not logged in', () => {
			init();
			mockContext.isUserLoggedIn = false;
			const revertStatusSpy = jest.fn();
			mockStore.actions.revertStatus = revertStatusSpy;

			const generator = mockStore.actions.updateUserPreferences();
			generator.next();

			expect( mockContext.loginRequired ).toBe( true );
			expect( revertStatusSpy ).toHaveBeenCalled();
		} );

		it( 'should call addLike with correct parameters if user is logged in', async () => {
			init();

			const generator = mockStore.actions.updateUserPreferences();
			await generator.next();

			expect( addLike ).toHaveBeenCalledWith( {
				meta: {
					_like: {
						id: mockContext.id,
						type: mockContext.type,
						isActive: mockContext.isActive,
					},
				},
			} );
		} );

		it( 'should handle errors by setting error context and reverting status', () => {
			init();
			mockContext.isUserLoggedIn = true;
			const error = {
				code: 'ERROR_CODE',
				message: 'Error message',
				data: { status: 500 },
			};

			const generator = mockStore.actions.updateUserPreferences();
			generator.next();
			generator.throw( error );

			expect( mockContext.error ).toEqual( {
				code: error.code,
				message: error.message,
			} );
		} );
	} );
} );
