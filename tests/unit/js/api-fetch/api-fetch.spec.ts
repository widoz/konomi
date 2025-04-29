import { jest, describe, it, expect } from '@jest/globals';

describe( 'Api Fetch', () => {
	it( 'should set the catchErrors middleware', async () => {
		const useSpy = jest.spyOn(window['wp'].apiFetch, 'use');
		await import('../../../../sources/ApiFetch/client/index.ts');
		expect(useSpy.mock.calls[0][0].name).toEqual('catchErrors');
	} );
} );
