import { apiFetch } from '@konomi/api-fetch';

type Payload = Readonly< {
	meta: Readonly< {
		_bookmark: Readonly< {
			id: number;
			type: string;
			isActive: boolean;
		} >;
	} >;
} >;

export function addBookmark( payload: Payload ): Promise< void > {
	return apiFetch( {
		path: '/konomi/v1/user-bookmark/',
		method: 'POST',
		data: payload,
	} );
}
