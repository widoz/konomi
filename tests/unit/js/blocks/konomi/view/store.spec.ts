import { jest, describe, it, expect, beforeEach } from '@jest/globals';
import { fromPartial } from '@total-typescript/shoehorn';
import type { h } from 'preact';

import {
	getContext,
	getElement,
	useLayoutEffect,
	store,
} from '@wordpress/interactivity';

import * as popoverModule from '../../../../../../sources/Blocks/Konomi/view/popover';
import { loginModalElement } from '../../../../../../sources/Blocks/Konomi/view/elements/login-modal-element';
import { init } from '../../../../../../sources/Blocks/Konomi/view/store';

import type { Context } from '../../../../../../sources/Blocks/Konomi/view/store';

// Mock dependencies
jest.mock( '@wordpress/interactivity', () => ( {
	getContext: jest.fn(),
	getElement: jest.fn(),
	store: jest.fn(),
	useLayoutEffect: jest.fn(),
} ) );

jest.mock(
	'../../../../../../sources/Blocks/Konomi/view/elements/login-modal-element',
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
	let mockContext: Context;

	beforeEach( () => {
		jest.clearAllMocks();

		// Mock renderMessage to return a Promise
		jest.spyOn( popoverModule, 'renderMessage' ).mockImplementation( () =>
			Promise.resolve()
		);

		mockContext = {
			id: 1,
			type: 'post',
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
		describe( 'closeLoginModal', () => {
			it( 'should set loginRequired to false', () => {
				init();
				mockContext.loginRequired = true;
				mockStore.actions.closeLoginModal();
				expect( mockContext.loginRequired ).toBe( false );
			} );
		} );
	} );

	describe( 'callbacks', () => {
		describe( 'maybeRenderResponseError', () => {
			it( 'should call useLayoutEffect with correct dependencies', () => {
				init();
				mockGetElementReturnValue( document.createElement( 'div' ) );
				stubUseLayoutEffect();
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
				mockGetElementReturnValue( elementRef );

				const renderPromise = Promise.resolve();
				jest.spyOn( popoverModule, 'renderMessage' ).mockReturnValue(
					renderPromise
				);

				const layoutEffectCallback = stubUseLayoutEffect();
				mockStore.callbacks.maybeRenderResponseError();

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

				mockGetElementReturnValue( document.createElement( 'div' ) );

				const renderPromise = Promise.resolve();
				jest.spyOn( popoverModule, 'renderMessage' ).mockReturnValue(
					renderPromise
				);

				stubUseLayoutEffect();
				mockStore.callbacks.maybeRenderResponseError();

				await renderPromise;

				expect( popoverModule.renderMessage ).not.toHaveBeenCalled();
			} );

			it( 'should not call renderMessage if element ref is not an HTMLElement', async () => {
				mockContext.error = {
					code: 'ERROR_CODE',
					message: 'Error message',
				};
				init();

				mockGetElementReturnValue( null );

				const renderPromise = Promise.resolve();
				jest.spyOn( popoverModule, 'renderMessage' ).mockReturnValue(
					renderPromise
				);

				stubUseLayoutEffect();
				mockStore.callbacks.maybeRenderResponseError();

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
				mockGetElementReturnValue( elementRef );

				const renderPromise = Promise.resolve();
				jest.spyOn( popoverModule, 'renderMessage' ).mockReturnValue(
					renderPromise
				);

				stubUseLayoutEffect();
				mockStore.callbacks.maybeRenderResponseError();

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
				mockGetElementReturnValue( elementRef );

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
				mockGetElementReturnValue( elementRef );

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
				mockGetElementReturnValue( null );
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
function mockGetElementReturnValue( element: HTMLElement | null ): Element {
	const returnValue = fromPartial< Element >( { ref: element } );
	jest.mocked( getElement ).mockReturnValue( returnValue );
	return returnValue;
}

/**
 * Cannot use @testing-library/preact because of some issues with the modules.
 * @link https://github.com/testing-library/preact-testing-library/issues/70
 */
function stubUseLayoutEffect(): void {
	jest.mocked( useLayoutEffect ).mockImplementationOnce(
		( callback: () => void ) => callback()
	);
}
