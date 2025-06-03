/**
 * External dependencies
 */
const path = require('path');

/**
 * WordPress dependencies
 */
const baseConfiguration = require('@wordpress/scripts/config/webpack.config');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

/**
 * Internal dependencies
 */
const tsConfig = require('./tsconfig.json');
const makeAliases = require('./.scripts/make-aliases');

const EXTRACTION_CONFIGURATION = {
	'@konomi/configuration': ['konomiConfiguration', 'konomi-configuration'],
	'@konomi/icons': ['konomiIcons', 'konomi-icons'],
};

const configuration = {
	...baseConfiguration,
	plugins: [
		...baseConfiguration.plugins.filter((plugin) => ![
			'DependencyExtractionWebpackPlugin',
			'CopyPlugin',
			'RtlCssPlugin',
		].includes(plugin.constructor.name)),
		new DependencyExtractionWebpackPlugin({
			outputFormat: 'php',
			requestToExternal: (request) => {
				if (EXTRACTION_CONFIGURATION[ request ]) {
					return EXTRACTION_CONFIGURATION[ request ]?.[ 0 ];
				}

				return undefined;
			},
			requestToHandle: (request) => {
				if (EXTRACTION_CONFIGURATION[ request ]) {
					return EXTRACTION_CONFIGURATION[ request ]?.[ 1 ];
				}

				return undefined;
			},
		}),
	],
	resolve: {
		...baseConfiguration.resolve,
		alias: makeAliases(baseConfiguration.resolve.alias, tsConfig, __dirname),
	},
	output: {},
};

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
		name: 'konomi-configuration',
	},

	/*
	 * The Icons build
	 */
	{
		...configuration,
		entry: {
			'konomi-icons': './sources/Icons/client/index.ts',
		},
		module: {
			...configuration.module,
			rules: [
				...configuration.module.rules,
				{
					test: /\.svg$/i,
					issuer: /\.[jt]sx?$/,
					use: [{ loader: '@svgr/webpack', options: { icon: true } }],
				},
			],
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
		name: 'konomi-icons',
	},

	/*
	 * The Konomi main block build
	 */
	{
		...configuration,
		entry: {
			'konomi-konomi-block': './sources/Blocks/Konomi/index.ts',
		},
		output: {
			filename: '[name].js',
			path: path.resolve('./sources/Blocks/Konomi/dist/js'),
			clean: true,
		},
	},
	{
		...configuration,
		entry: {
			'konomi-konomi-block': './sources/Blocks/Konomi/view/style.scss',
		},
		output: {
			path: path.resolve('./sources/Blocks/Konomi/dist/css'),
			clean: true,
		},
		plugins: [
			...configuration.plugins.filter(plugin => plugin.constructor.name !== 'CleanWebpackPlugin'),
			cleanPluginFor('konomi'),
		],
	},

	/*
	 * The Reaction block build
	 */
	{
		...configuration,
		entry: {
			'konomi-reaction-block': './sources/Blocks/Reaction/index.ts',
		},
		output: {
			filename: '[name].js',
			path: path.resolve('./sources/Blocks/Reaction/dist/js'),
			clean: true,
		},
	},
	{
		...configuration,
		entry: {
			'konomi-reaction-block': './sources/Blocks/Reaction/view/style.scss',
		},
		output: {
			path: path.resolve('./sources/Blocks/Reaction/dist/css'),
			clean: true,
		},
		plugins: [
			...configuration.plugins.filter(plugin => plugin.constructor.name !== 'CleanWebpackPlugin'),
			cleanPluginFor('reaction'),
		],
	},

	/*
	 * The Bookmark block build
	 */
	{
		...configuration,
		entry: {
			'konomi-bookmark-block': './sources/Blocks/Bookmark/index.ts',
		},
		output: {
			filename: '[name].js',
			path: path.resolve('./sources/Blocks/Bookmark/dist/js'),
			clean: true,
		},
	},
	{
		...configuration,
		entry: {
			'konomi-bookmark-block': './sources/Blocks/Bookmark/view/style.scss',
		},
		output: {
			path: path.resolve('./sources/Blocks/Bookmark/dist/css'),
			clean: true,
		},
		plugins: [
			...configuration.plugins.filter(plugin => plugin.constructor.name !== 'CleanWebpackPlugin'),
			cleanPluginFor('Bookmark'),
		],
	},
];

function cleanPluginFor(blockName) {
	const normalizedBlockName = blockName.charAt(0).toUpperCase() + blockName.slice(1);
	return new CleanWebpackPlugin({
		cleanAfterEveryBuildPatterns: [
			path.resolve(
				`./sources/Blocks/${normalizedBlockName}/dist/css/*.js`,
			),
			path.resolve(
				`./sources/Blocks/${normalizedBlockName}/dist/css/*.php`,
			),
		],
	});
}
