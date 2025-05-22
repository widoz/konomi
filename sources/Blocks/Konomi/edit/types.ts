import type { DeepReadonly } from '@konomi/types';

export namespace KonomiEdit {
	export type Attributes = Readonly< {
		allowedBlocks: Array< string >;
	} >;

	export type EditProps = DeepReadonly< {
		attributes: Attributes;
		setAttributes: (
			newAttributes: Partial< DeepReadonly< Attributes > >
		) => void;
	} >;
}
