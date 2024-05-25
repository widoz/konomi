export = Wp;
export as namespace Wp;

import type Konomi from '@konomi/types';

declare namespace Wp {
	type SiteConfiguration = Readonly< {
		konomi?: Konomi.Configuration;
	} >;

	type Stores = Readonly< {
		core: {
			select: {
				getSite: () => SiteConfiguration;
			};
		};
	} >;
}
