import type { Config } from 'jest';

type ModulesMapper = Config['moduleNameMapper']
type TsConfig = {
	compilerOptions: {
		paths: {
			[moduleName: string]: string[]
		}
	}
}

export function mapModulesFromTsConfig(tsConfig: TsConfig): ModulesMapper {
	const {
		compilerOptions: { paths },
	} = tsConfig;

	// @ts-ignore
	return Object.entries( paths ).reduce(
		// @ts-ignore
		( acc, [ moduleName, entry ]: [ string, string[] ] ) => {
			const value = entry[0] as string;
			const modulePath = value.replace( /^\.\//, '' );

			return {
				...acc,
				[moduleName]: `<rootDir>/${ modulePath }`,
			};
		},
		{},
	);
}
