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

const configuration = {
	...baseConfiguration,
	plugins: [
		...baseConfiguration.plugins.filter((plugin) => {
			return (
			  plugin.constructor.name !==
			  'DependencyExtractionWebpackPlugin' &&
			  plugin.constructor.name !== 'CopyPlugin'
			)
		}),
		new DependencyExtractionWebpackPlugin({
			outputFormat: 'php',
		}),
	],
	resolve: {
		...baseConfiguration.resolve,
		alias: makeAliases(baseConfiguration.resolve.alias, tsConfig, __dirname),
	},
	output: {},
}

module.exports = [
	{
		...configuration,
		entry: {
			like: './sources/Blocks/like/index.ts',
		},
		output: {
			filename: '[name].js',
			path: path.resolve('./sources/Blocks/like/dist'),
			clean: true,
		},
	},
]
