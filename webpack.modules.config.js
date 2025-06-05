/**
 * External dependencies
 */
const path = require('path');
/**
 * WordPress dependencies
 */
const [, moduleConfiguration] = require('@wordpress/scripts/config/webpack.config');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
/**
 * Internal dependencies
 */
const tsConfig = require('./tsconfig.json');
const makeAliases = require('./.scripts/make-aliases');

const EXTRACTION_CONFIGURATION = [
	'@konomi/configuration',
	'@konomi/api-fetch',
	'@konomi/icons',
];

const configuration = {
	...moduleConfiguration,
	plugins: [
		...moduleConfiguration.plugins.filter((plugin) => ![
			'DependencyExtractionWebpackPlugin',
			'CopyPlugin',
			'RtlCssPlugin',
		].includes(plugin.constructor.name)),
		new DependencyExtractionWebpackPlugin({
			outputFormat: 'php',
			requestToExternalModule: (request) => {
				return EXTRACTION_CONFIGURATION.includes(request)
			},
		}),
	],
	resolve: {
		...moduleConfiguration.resolve,
		alias: makeAliases(moduleConfiguration.resolve.alias, tsConfig, __dirname),
	},
	output: {
		...moduleConfiguration.output,
		module: true,
		chunkFormat: 'module',
		environment: {
			...moduleConfiguration.output.environment,
			module: true,
		},
		library: {
			...moduleConfiguration.output.library,
			type: 'module',
		},
	},
};

module.exports = [
	/*
	 * Configuration Module
	 */
	{
		...configuration,
		entry: {
			'konomi-configuration': './sources/Configuration/client/index.ts',
		},
		output: {
			...configuration.output,
			filename: '[name].js',
			path: path.resolve('./sources/Configuration/client/build-module'),
			clean: true,
		},
	},

	/*
	 * Api Fetch Module
	 */
	{
		...configuration,
		entry: {
			'konomi-api-fetch': './sources/ApiFetch/client/index.ts',
		},
		output: {
			...configuration.output,
			filename: '[name].js',
			path: path.resolve('./sources/ApiFetch/client/build-module'),
			clean: true,
		},
	},

	/*
	 * Konomi Block Module
	 */
	{
		...configuration,
		entry: {
			'konomi-konomi-block-view': './sources/Blocks/Konomi/view/index.ts',
		},
		output: {
			...configuration.output,
			filename: '[name].js',
			path: path.resolve('./sources/Blocks/Konomi/build-module'),
			clean: true,
		},
	},

	/*
	 * Reaction Block Module
	 */
	{
		...configuration,
		entry: {
			'konomi-reaction-block-view': './sources/Blocks/Reaction/view/index.ts',
		},
		output: {
			...configuration.output,
			filename: '[name].js',
			path: path.resolve('./sources/Blocks/Reaction/build-module'),
			clean: true,
		},
	},

	/*
	 * Bookmark Module
	 */
	{
		...configuration,
		entry: {
			'konomi-bookmark-block-view': './sources/Blocks/Bookmark/view/index.ts',
		},
		output: {
			...configuration.output,
			filename: '[name].js',
			path: path.resolve('./sources/Blocks/Bookmark/build-module'),
			clean: true,
		},
	}
]
