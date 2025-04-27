import { jest, describe, it, expect, beforeEach } from '@jest/globals';
import { fromPartial } from '@total-typescript/shoehorn';
import type { h } from 'preact';
import { act } from 'preact/test-utils';

import {
	getContext,
	getElement,
	useLayoutEffect,
	store,
} from '@wordpress/interactivity';

import { init } from '../../../../../../sources/Blocks/Like/view/store';
import { addLike } from '../../../../../../sources/Blocks/Like/view/add-like-command';
import * as popoverModule from '../../../../../../sources/Blocks/Like/view/popover';
import { loginModalElement } from '../../../../../../sources/Blocks/Like/view/elements/login-modal-element';

// Mock dependencies
jest.mock( '@wordpress/interactivity', () => ( {
	getContext: jest.fn(),
	getElement: jest.fn(),
	store: jest.fn(),
	useLayoutEffect: jest.fn( ( callback: () => void ) => callback() ),
} ) );

jest.mock(
	'../../../../../../sources/Blocks/Like/view/add-like-command',
	() => ( {
		addLike: jest.fn(),
	} )
);

jest.mock(
	'../../../../../../sources/Blocks/Like/view/elements/login-modal-element',
	() => ( {
		loginModalElement: jest.fn(),
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
		error?: {
			code: string;
			message: string;
		};
	};

	beforeEach( () => {
		jest.clearAllMocks();

		// Mock renderMessage to return a Promise
		jest.spyOn( popoverModule, 'renderMessage' ).mockImplementation( () =>
			Promise.resolve()
		);

		mockContext = {
			id: 1,
			type: 'post',
			isActive: false,
			count: 5,
			isUserLoggedIn: true,
			loginRequired: false,
			error: {
				code: '',
				message: '',
			},
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

		describe( 'closeLoginModal', () => {
			it( 'should set loginRequired to false', () => {
				init();
				mockContext.loginRequired = true;
				mockStore.actions.closeLoginModal();
				expect( mockContext.loginRequired ).toBe( false );
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

	describe( 'callbacks', () => {
		describe( 'maybeRenderResponseError', () => {
			it( 'should call useLayoutEffect with correct dependencies', () => {
				init();
				jest.mocked( getElement ).mockReturnValue(
					mockElement( document.createElement( 'div' ) )
				);
				mockStore.callbacks.maybeRenderResponseError();
				expect( useLayoutEffect ).toHaveBeenCalledWith(
					expect.any( Function ),
					[ mockContext.error?.code, mockContext.error?.message ]
				);
			} );

			it( 'should call renderMessage with the contextual element if context has error', async () => {
				mockContext.error = {
					code: 'ERROR_CODE',
					message: 'Error message',
				};
				init();

				const elementRef = document.createElement( 'div' );
				jest.mocked( getElement ).mockReturnValue(
					mockElement( elementRef )
				);

				const renderPromise = Promise.resolve();
				jest.spyOn( popoverModule, 'renderMessage' ).mockReturnValue(
					renderPromise
				);

				const layoutEffectCallback = mockUseLayoutEffect();
				mockStore.callbacks.maybeRenderResponseError();
				layoutEffectCallback();

				await renderPromise;

				expect( popoverModule.renderMessage ).toHaveBeenCalledWith(
					elementRef
				);
			} );

			it( 'should not call renderMessage if context does not have error', async () => {
				mockContext.error = {
					code: '',
					message: '',
				};
				init();

				jest.mocked( getElement ).mockReturnValue(
					mockElement( document.createElement( 'div' ) )
				);

				const renderPromise = Promise.resolve();
				jest.spyOn( popoverModule, 'renderMessage' ).mockReturnValue(
					renderPromise
				);

				const layoutEffectCallback = mockUseLayoutEffect();
				mockStore.callbacks.maybeRenderResponseError();
				layoutEffectCallback();

				await renderPromise;

				expect( popoverModule.renderMessage ).not.toHaveBeenCalled();
			} );

			it( 'should not call renderMessage if element ref is not an HTMLElement', async () => {
				mockContext.error = {
					code: 'ERROR_CODE',
					message: 'Error message',
				};
				init();

				jest.mocked( getElement ).mockReturnValue(
					mockElement( null )
				);

				const renderPromise = Promise.resolve();
				jest.spyOn( popoverModule, 'renderMessage' ).mockReturnValue(
					renderPromise
				);

				const layoutEffectCallback = mockUseLayoutEffect();
				mockStore.callbacks.maybeRenderResponseError();
				layoutEffectCallback();

				await renderPromise;

				expect( popoverModule.renderMessage ).not.toHaveBeenCalled();
			} );

			it( 'should reset error context after renderMessage completes', async () => {
				mockContext.error = {
					code: 'ERROR_CODE',
					message: 'Error message',
				};
				init();

				const elementRef = document.createElement( 'div' );
				jest.mocked( getElement ).mockReturnValue(
					mockElement( elementRef )
				);

				const renderPromise = Promise.resolve();
				jest.spyOn( popoverModule, 'renderMessage' ).mockReturnValue(
					renderPromise
				);

				const layoutEffectCallback = mockUseLayoutEffect();
				mockStore.callbacks.maybeRenderResponseError();
				layoutEffectCallback();

				await renderPromise;

				expect( mockContext.error ).toEqual( {
					code: '',
					message: '',
				} );
			} );
		} );

		describe( 'toggleLoginModal', () => {
			it( 'should show modal when loginRequired is true', () => {
				mockContext.loginRequired = true;
				init();

				const elementRef = document.createElement( 'div' );
				jest.mocked( getElement ).mockReturnValue(
					mockElement( elementRef )
				);

				const mockDialog = fromPartial< HTMLDialogElement >( {
					showModal: jest.fn(),
					close: jest.fn(),
				} );
				jest.mocked( loginModalElement ).mockImplementationOnce(
					( element: any ) => {
						if ( element !== elementRef ) {
							throw new Error( 'Element ref mismatch' );
						}
						return mockDialog;
					}
				);

				mockStore.callbacks.toggleLoginModal();

				expect( mockDialog.showModal ).toHaveBeenCalled();
				expect( mockDialog.close ).not.toHaveBeenCalled();
			} );

			it( 'should close modal when loginRequired is false', () => {
				mockContext.loginRequired = false;
				init();

				const elementRef = document.createElement( 'div' );
				jest.mocked( getElement ).mockReturnValue(
					mockElement( elementRef )
				);

				const mockDialog = fromPartial< HTMLDialogElement >( {
					showModal: jest.fn(),
					close: jest.fn(),
				} );
				jest.mocked( loginModalElement ).mockImplementationOnce(
					( element: any ) => {
						if ( element !== elementRef ) {
							throw new Error( 'Element ref mismatch' );
						}
						return mockDialog;
					}
				);

				mockStore.callbacks.toggleLoginModal();

				expect( mockDialog.close ).toHaveBeenCalled();
				expect( mockDialog.showModal ).not.toHaveBeenCalled();
			} );

			it( 'should do nothing when element ref is not an HTMLElement', () => {
				init();
				jest.mocked( getElement ).mockReturnValue(
					mockElement( null )
				);
				mockStore.callbacks.toggleLoginModal();
				expect( loginModalElement ).not.toHaveBeenCalled();
			} );
		} );
	} );
} );

type Element = {
	ref: HTMLElement | null;
	attributes: h.JSX.HTMLAttributes< EventTarget >;
};
function mockElement( element: HTMLElement | null ): Element {
	return fromPartial< Element >( { ref: element } );
}

type Callback = () => void;
/**
 * Cannot use @testing-library/preact because of some issues with the modules.
 */
function mockUseLayoutEffect(): Callback {
	let layoutEffectCallback: Callback = () => {};
	jest.mocked( useLayoutEffect ).mockImplementationOnce( ( callback ) => {
		layoutEffectCallback = callback;
		return callback();
	} );
	return layoutEffectCallback;
}
