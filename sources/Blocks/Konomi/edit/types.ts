export namespace KonomiEdit {
	export type Attributes = Readonly< {
		allowedBlocks: Array< string >;
	} >;

	export type EditProps = Readonly< {
		attributes: Attributes;
		setAttributes: ( newAttributes: Partial< Attributes > ) => void;
	} >;
}
