export = Konomi;
export as namespace Konomi;

declare namespace Konomi {
	type Configuration = Readonly< {
		iconsPathUrl: URL;
	} >;
}

declare global {
	interface Window {
		wp: {
			apiFetch: ( options: any ) => Promise< any >;
		};
	}
}
