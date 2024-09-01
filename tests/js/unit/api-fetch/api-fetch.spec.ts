import { jest, describe, it, expect } from '@jest/globals';
import { apiFetch } from '../../../../sources/ApiFetch/client';

describe( 'fetch', () => {
	it( 'it should be a function', () => {
		expect( apiFetch ).toBeInstanceOf( Function );
	} );
} );
