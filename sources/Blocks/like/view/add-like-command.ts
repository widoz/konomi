const { apiFetch } = window.wp;

type Payload = Readonly< {
	meta: Readonly< {
		_like: Readonly< {
			id: number;
			type: string;
			isActive: boolean;
		} >;
	} >;
} >;

export function addLike( payload: Payload ): Promise< void > {
	return apiFetch( {
		path: '/konomi/v1/user-like/',
		method: 'POST',
		data: payload,
	} );
}
