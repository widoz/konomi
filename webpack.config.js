/**
 * External dependencies
 */
const path = require('path')
/**
 * WordPress dependencies
 */
const baseConfiguration = require('@wordpress/scripts/config/webpack.config')
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin')
/**
 * Internal dependencies
 */
const tsConfig = require('./tsconfig.json')
const makeAliases = require('./.scripts/make-aliases')

const EXTRACTION_CONFIGURATION = {
	'@konomi/configuration': ['konomiConfiguration', 'konomi-configuration'],
	'@konomi/icons': ['konomiIcons', 'konomi-icons']
}

const [baseScriptConfig, baseModuleConfig] = baseConfiguration

const configuration = {
	...baseScriptConfig,
	plugins: [
		...baseScriptConfig.plugins.filter((plugin) => ![
			'DependencyExtractionWebpackPlugin',
			'CopyPlugin',
			'RtlCssPlugin'
		].includes(plugin.constructor.name)),
		new DependencyExtractionWebpackPlugin({
			outputFormat: 'php',
			requestToExternal: ( request ) => {
				if ( EXTRACTION_CONFIGURATION[ request ] ) {
					return EXTRACTION_CONFIGURATION[ request ]?.[ 0 ];
				}

				return undefined;
			},
			requestToHandle: ( request ) => {
				if ( EXTRACTION_CONFIGURATION[ request ] ) {
					return EXTRACTION_CONFIGURATION[ request ]?.[ 1 ];
				}

				return undefined;
			},
		}),
	],
	resolve: {
		...baseScriptConfig.resolve,
		alias: makeAliases(baseScriptConfig.resolve.alias, tsConfig, __dirname),
	},
	output: {},
}

module.exports = [
	/*
	 * The Configuration build
	 */
	{
		...configuration,
		entry: {
			'konomi-configuration': './sources/Configuration/client/index.ts',
		},
		output: {
			filename: '[name].js',
			path: path.resolve('./sources/Configuration/client/dist'),
			clean: true,
			library: {
				name: 'konomiConfiguration',
				type: 'window',
			},
		},
		name: 'konomi-configuration'
	},

	/*
	 * The Icons build
	 */
	{
		...configuration,
		entry: {
			'konomi-icons': './sources/Icons/client/index.ts',
		},
		output: {
			filename: '[name].js',
			path: path.resolve('./sources/Icons/client/dist'),
			clean: true,
			library: {
				name: 'konomiIcons',
				type: 'window',
			},
		},
		name: 'konomi-icons'
	},

	/*
	 * The Like block build
	 */
	{
		...configuration,
		entry: {
			'konomi-like-block': './sources/Blocks/like/index.ts',
		},
		output: {
			filename: '[name].js',
			path: path.resolve('./sources/Blocks/like/dist'),
			clean: true,
		},
	},
	{
		...baseModuleConfig,
		entry: {
			'konomi-like-block-view': './sources/Blocks/like/view.ts',
		},
		output: {
			...baseModuleConfig.output,
			filename: '[name].js',
			path: path.resolve('./sources/Blocks/like/module'),
			clean: true,
		},
	}
]
