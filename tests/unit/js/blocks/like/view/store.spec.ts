import { jest, describe, it, expect, beforeEach } from '@jest/globals';

import {
	getContext,
	store,
} from '@wordpress/interactivity';

import { init } from '../../../../../../sources/Blocks/Reaction/view/store';
import { addReaction } from '../../../../../../sources/Blocks/Reaction/view/add-reaction-command';

import type { Context as OuterContext } from '../../../../../../sources/Blocks/Konomi/view/store';
import type { Context } from '../../../../../../sources/Blocks/Reaction/view/store';

// Mock dependencies
jest.mock( '@wordpress/interactivity', () => ( {
	getContext: jest.fn(),
	getElement: jest.fn(),
	store: jest.fn(),
	useLayoutEffect: jest.fn(),
} ) );

jest.mock(
	'../../../../../../sources/Blocks/Reaction/view/add-reaction-command',
	() => ( {
		addReaction: jest.fn(),
	} ),
);

describe( 'Interactivity Store', () => {
	let mockNamespace: string;
	let mockStore: {
		state: Record<string, unknown>;
		actions: Record<string, ( ...args: any[] ) => void>;
	};
	let mockContext: Context;
	let outerMockContext: OuterContext;

	beforeEach( () => {
		jest.clearAllMocks();

		mockContext = {
			isActive: false,
			count: 5,
		};
		outerMockContext = {
			id: 1,
			type: 'post',
			isUserLoggedIn: true,
			loginRequired: false,
			error: {
				code: '',
				message: '',
			},
		};
		// @ts-ignore
		jest.mocked( getContext ).mockImplementation( ( name?: string ) => {
			switch ( name ) {
				case 'konomi': return outerMockContext;
				case 'konomiReaction': return mockContext;
			}
		} );

		mockStore = { state: {}, actions: {} };
		jest.mocked( store ).mockImplementation(
			( namespace: string, config: any ) => {
				mockNamespace = namespace;
				mockStore = config;
				return { actions: config.actions };
			},
		);
	} );

	describe( 'store', () => {
		it( 'should be initialized with the correct namespace', () => {
			init();
			expect( mockNamespace ).toEqual( 'konomiReaction' );
		} );
	} );

	describe( 'actions', () => {
		describe( 'toggleStatus', () => {
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
				mockStore.actions.updateUserPreferences =
					updateUserPreferencesSpy;
				mockStore.actions.toggleStatus();
				expect( updateUserPreferencesSpy ).toHaveBeenCalledTimes( 1 );
			} );
		} );

		describe( 'revertStatus', () => {
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

		describe( 'updateUserPreferences', () => {
			it( 'should set loginRequired and revert status if user is not logged in', () => {
				init();
				outerMockContext.isUserLoggedIn = false;
				const revertStatusSpy = jest.fn();
				mockStore.actions.revertStatus = revertStatusSpy;

				const generator = mockStore.actions.updateUserPreferences();
				generator.next();

				expect( outerMockContext.loginRequired ).toBe( true );
				expect( revertStatusSpy ).toHaveBeenCalled();
			} );

			it( 'should call addReaction with correct parameters if user is logged in', async () => {
				init();

				const generator = mockStore.actions.updateUserPreferences();
				await generator.next();

				expect( addReaction ).toHaveBeenCalledWith( {
					meta: {
						_reaction: {
							id: outerMockContext.id,
							type: outerMockContext.type,
							isActive: mockContext.isActive,
						},
					},
				} );
			} );

			it( 'should handle errors by setting error context and reverting status', () => {
				init();
				outerMockContext.isUserLoggedIn = true;
				const error = {
					code: 'ERROR_CODE',
					message: 'Error message',
					data: { status: 500 },
				};

				const generator = mockStore.actions.updateUserPreferences();
				generator.next();
				generator.throw( error );

				expect( outerMockContext.error ).toEqual( {
					code: error.code,
					message: error.message,
				} );
			} );
		} );
	} );
} );
