import { jest, describe, it, expect, beforeEach } from '@jest/globals';

import {
	getContext,
	store,
} from '@wordpress/interactivity';

import { init } from '../../../../../../sources/Blocks/Bookmark/view/store';
import { addBookmark } from '../../../../../../sources/Blocks/Bookmark/view/add-bookmark-command';

import type { Context as OuterContext } from '../../../../../../sources/Blocks/Konomi/view/store';
import type { Context } from '../../../../../../sources/Blocks/Bookmark/view/store';

// Mock dependencies
jest.mock( '@wordpress/interactivity', () => ( {
	getContext: jest.fn(),
	getElement: jest.fn(),
	store: jest.fn(),
	useLayoutEffect: jest.fn(),
} ) );

jest.mock(
	'../../../../../../sources/Blocks/Bookmark/view/add-bookmark-command',
	() => ( {
		addBookmark: jest.fn(),
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
				case 'konomiBookmark': return mockContext;
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
			expect( mockNamespace ).toEqual( 'konomiBookmark' );
		} );
	} );

	describe( 'actions', () => {
		describe( 'toggleStatus', () => {
			it( 'should toggle isActive from false to true', () => {
				init();
				mockContext.isActive = false;
				mockStore.actions.toggleStatus();
				expect( mockContext.isActive ).toBe( true );
			} );

			it( 'should toggle isActive from true to false', () => {
				init();
				mockContext.isActive = true;
				mockStore.actions.toggleStatus();
				expect( mockContext.isActive ).toBe( false );
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
			it( 'should revert isActive status from true to false', () => {
				init();
				mockContext.isActive = true;
				mockStore.actions.revertStatus();
				expect( mockContext.isActive ).toBe( false );
			} );

			it( 'should revert isActive status from false to true', () => {
				init();
				mockContext.isActive = false;
				mockStore.actions.revertStatus();
				expect( mockContext.isActive ).toBe( true );
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

			it( 'should call addBookmark with correct parameters if user is logged in', async () => {
				init();

				const generator = mockStore.actions.updateUserPreferences();
				await generator.next();

				expect( addBookmark ).toHaveBeenCalledWith( {
					meta: {
						_bookmark: {
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
