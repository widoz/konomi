export = Konomi;
export as namespace Konomi;

declare namespace Konomi {
	type Configuration = Readonly< {
		iconsPathUrl: URL;
		restUrl: string;
	} >;
}
