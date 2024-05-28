const path = require('path')

function makeAliases (externalAliases, tsConfig, basePath) {
	const aliases = {}

	const paths = tsConfig?.compilerOptions?.paths ?? null
	if (typeof paths !== 'object') {
		return aliases
	}

	for (const [key, values] of Object.entries(paths)) {
		if (!Array.isArray(values) || values[0] === undefined) {
			continue
		}
		if (key.includes('@wordpress')) {
			continue
		}

		aliases[key] = path.resolve(basePath, values[0])
	}

	return {
		...externalAliases,
		...aliases,
		'@konomi/functions': path.resolve(basePath, 'resources/scss/functions/index.scss'),
	}
}

module.exports = makeAliases
