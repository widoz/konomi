import { APIFetchMiddleware, APIFetchOptions } from '@wordpress/api-fetch';

export = Konomi;
export as namespace Konomi;

declare namespace Konomi {
	type Configuration = Readonly< {
		iconsPathUrl: URL;
		isDebugMode: boolean;
	} >;
}

declare global {
	interface APIFetch {
		(options: APIFetchOptions): Promise<any>;
		use: (middleware: APIFetchMiddleware) => void;
		setFetchHandler: (handler: (options: APIFetchOptions) => Promise<any>) => void;
	}

	interface Window {
		wp: {
			apiFetch: APIFetch;
		};
	}
}
