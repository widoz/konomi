import { jest, describe, it, expect } from '@jest/globals';
import { fromPartial } from '@total-typescript/shoehorn';
import { configuration } from '@konomi/configuration';
import { catchErrors } from '../../../../../sources/ApiFetch/client/middlewares/catch-errors';

jest.mock( '@konomi/configuration', () => ( {
	configuration: jest.fn(),
} ) );

describe( 'Catch Errors Middleware', () => {
	it( 'should log the error in console if Debug mode is active', ( done ) => {
		console.log = jest.fn( () => {
			expect( console.log ).toHaveBeenCalledWith( 'Error' );
			done();
		} );

		jest.mocked( configuration ).mockImplementation( () =>
			fromPartial( { isDebugMode: true } )
		);

		const next = jest.fn( () => Promise.reject( 'Error' ) );

		catchErrors( {}, next );
	} );

	it( 'should not log the error in console if Debug mode is not active', () => {
		console.log = jest.fn( () => {
			throw new Error( 'This should not be called' );
		} );

		jest.mocked( configuration ).mockImplementation( () =>
			fromPartial( { isDebugMode: false } )
		);

		const next = jest.fn( () => Promise.reject( 'Error' ) );

		catchErrors( {}, next );
	} );
} );
