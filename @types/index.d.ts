import type { APIFetchOptions, APIFetchMiddleware } from '@wordpress/api-fetch';

export declare global {
	export interface APIFetch {
		( options: APIFetchOptions ): Promise< any >;
		use: ( middleware: APIFetchMiddleware ) => void;
		setFetchHandler: (
			handler: ( options: APIFetchOptions ) => Promise< any >
		) => void;
	}

	export interface Window {
		wp: {
			apiFetch: APIFetch;
		};
	}
}
