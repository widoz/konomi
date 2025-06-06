import { describe, beforeEach, jest, it, expect } from '@jest/globals';
import { apiFetch } from '@konomi/api-fetch';
import { addReaction } from '../../../../../../sources/Blocks/Reaction/view/add-reaction-command';

jest.mock( '@konomi/api-fetch', () => ( {
	apiFetch: jest.fn(),
} ) );

describe( 'addReaction', () => {
	beforeEach( () => {
		jest.clearAllMocks();
	} );

	it( 'should call apiFetch with the correct parameters', async () => {
		const mockPayload = {
			meta: {
				_reaction: {
					id: 123,
					type: 'post',
					isActive: true,
				},
			},
		};

		jest.mocked( apiFetch ).mockResolvedValue( undefined );

		await addReaction( mockPayload );

		expect( apiFetch ).toHaveBeenCalledWith( {
			path: '/konomi/v1/user-reaction/',
			method: 'POST',
			data: mockPayload,
		} );
	} );

	it( 'should return a promise from apiFetch', async () => {
		const mockPayload = {
			meta: {
				_reaction: {
					id: 456,
					type: 'comment',
					isActive: false,
				},
			},
		};

		jest.mocked( apiFetch ).mockResolvedValue( 'success' );

		const result = addReaction( mockPayload );

		await expect( result ).resolves.toBe( 'success' );
		expect( apiFetch ).toHaveBeenCalled();
	} );

	it( 'should propagate errors from apiFetch', async () => {
		const mockPayload = {
			meta: {
				_reaction: {
					id: 789,
					type: 'post',
					isActive: true,
				},
			},
		};

		const mockError = new Error( 'API error' );
		jest.mocked( apiFetch ).mockRejectedValue( mockError );

		await expect( addReaction( mockPayload ) ).rejects.toThrow( 'API error' );
		expect( apiFetch ).toHaveBeenCalled();
	} );
} );
