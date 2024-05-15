/**
 * WordPress dependencies
 */
const baseConfiguration = require( '@wordpress/scripts/config/webpack.config' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
/**
 * External dependencies
 */
const path = require( 'path' );

const configuration = {
	...baseConfiguration,
	plugins: [
		...baseConfiguration.plugins.filter((plugin) => {
			return plugin.constructor.name !== 'DependencyExtractionWebpackPlugin' && plugin.constructor.name !== 'CopyPlugin'
		}),
		new DependencyExtractionWebpackPlugin({
			outputFormat: 'php',
		}),
	],
	resolve: {
		...baseConfiguration.resolve,
		alias: {
			...baseConfiguration.resolve.alias,
			'@types': path.resolve(__dirname, './@types/index.d.ts'),
		},
	},
	output: {},
}

module.exports = [
	{
		...configuration,
		entry: {
			'like': './sources/Blocks/like/index.ts',
		},
		output: {
			filename: '[name].js',
			path: path.resolve('./sources/Blocks/like/dist'),
			clean: true,
		},
	},
]
