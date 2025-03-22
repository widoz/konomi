import { jest, describe, it, expect, beforeEach } from '@jest/globals';
import { findInteractivityParent } from '../../../../../../sources/Blocks/Like/view/utils';
import {
	loginModalElement,
} from '../../../../../../sources/Blocks/Like/view/elements/login-modal-element';

jest.mock( '../../../../../../sources/Blocks/Like/view/utils', () => ({
	findInteractivityParent: jest.fn(),
}) );

describe( 'loginModalElement', () => {
	const VALID_DIALOG_MARKUP = `
    <div data-wp-interactive="konomi">
      <dialog class="konomi-login-modal">Login Modal</dialog>
      <button class="trigger-button">Trigger</button>
    </div>
  `;

	const EMPTY_MARKUP = `
    <div data-wp-interactive="konomi">
      <button class="trigger-button">Trigger</button>
    </div>
  `;

	const parseHTML = ( html: string ) => {
		const parser = new DOMParser();
		const doc = parser.parseFromString( html, 'text/html' );
		return doc.body.firstElementChild as HTMLElement;
	};

	beforeEach( () => {
		jest.clearAllMocks();
	} );

	it( 'should return the dialog element when it exists', () => {
		const parent = parseHTML( VALID_DIALOG_MARKUP );
		const testElement = parent.querySelector( '.trigger-button' ) as HTMLElement;
		const dialog = parent.querySelector( '.konomi-login-modal' ) as HTMLDialogElement;
		jest.mocked( findInteractivityParent ).mockReturnValue( parent );
		const result = loginModalElement( testElement );
		expect( result ).toBe( dialog );
	} );

	it( 'should throw an error when dialog element is not found', () => {
		const parent = parseHTML( EMPTY_MARKUP );
		const testElement = parent.querySelector( '.trigger-button' ) as HTMLElement;
		jest.mocked( findInteractivityParent ).mockReturnValue( parent );
		expect( () => loginModalElement( testElement ) ).toThrow( 'Element is not an HTMLDialogElement' );
	} );

	it( 'should throw an error when parent is not found', () => {
		const parent = parseHTML( VALID_DIALOG_MARKUP );
		const testElement = parent.querySelector( '.trigger-button' ) as HTMLElement;
		jest.mocked( findInteractivityParent ).mockReturnValue( null );
		expect( () => loginModalElement( testElement ) ).toThrow( 'Element is not an HTMLDialogElement' );
	} );
} );
