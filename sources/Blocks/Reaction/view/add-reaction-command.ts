import { apiFetch } from '@konomi/api-fetch';

type Payload = Readonly< {
	meta: Readonly< {
		_reaction: Readonly< {
			id: number;
			type: string;
			isActive: boolean;
		} >;
	} >;
} >;

export function addReaction( payload: Payload ): Promise< void > {
	return apiFetch( {
		path: '/konomi/v1/user-reaction/',
		method: 'POST',
		data: payload,
	} );
}
