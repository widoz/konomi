import { jest, describe, it, expect, beforeEach } from '@jest/globals';

import { getContext, store } from '@wordpress/interactivity';

jest.mock( '@wordpress/interactivity', () => ( {
	getContext: jest.fn(),
	getElement: jest.fn(),
	store: jest.fn(),
} ) );

import { init } from '../../../../../../sources/Blocks/Like/view/store';

describe( 'Interactivity Store', () => {
	let mockNamespace: string;
	let mockStore: {
		state: Record<string, unknown>;
		actions: Record<string, ( ...args: any[] ) => void>;
		callbacks: Record<string, ( ...args: any[] ) => void>;
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
		jest.mocked( store ).mockImplementation( ( namespace: string, config: any ) => {
			mockNamespace = namespace;
			mockStore = config;
			return { actions: config.actions };
		} );
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
} );
