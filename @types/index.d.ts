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
