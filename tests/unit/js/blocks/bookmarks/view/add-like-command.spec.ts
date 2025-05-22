import { describe, beforeEach, jest, it, expect } from '@jest/globals';
import { apiFetch } from '@konomi/api-fetch';
import { addBookmark } from '../../../../../../sources/Blocks/Bookmark/view/add-bookmark-command';

jest.mock( '@konomi/api-fetch', () => ( {
	apiFetch: jest.fn(),
} ) );

describe( 'addBookmark', () => {
	beforeEach( () => {
		jest.clearAllMocks();
	} );

	it( 'should call apiFetch with the correct parameters', async () => {
		const mockPayload = {
			meta: {
				_bookmark: {
					id: 123,
					type: 'post',
					isActive: true,
				},
			},
		};

		jest.mocked( apiFetch ).mockResolvedValue( undefined );

		await addBookmark( mockPayload );

		expect( apiFetch ).toHaveBeenCalledWith( {
			path: '/konomi/v1/user-bookmark/',
			method: 'POST',
			data: mockPayload,
		} );
	} );

	it( 'should return a promise from apiFetch', async () => {
		const mockPayload = {
			meta: {
				_bookmark: {
					id: 456,
					type: 'comment',
					isActive: false,
				},
			},
		};

		jest.mocked( apiFetch ).mockResolvedValue( 'success' );

		const result = addBookmark( mockPayload );

		await expect( result ).resolves.toBe( 'success' );
		expect( apiFetch ).toHaveBeenCalled();
	} );

	it( 'should propagate errors from apiFetch', async () => {
		const mockPayload = {
			meta: {
				_bookmark: {
					id: 789,
					type: 'post',
					isActive: true,
				},
			},
		};

		const mockError = new Error( 'API error' );
		jest.mocked( apiFetch ).mockRejectedValue( mockError );

		await expect( addBookmark( mockPayload ) ).rejects.toThrow( 'API error' );
		expect( apiFetch ).toHaveBeenCalled();
	} );
} );
