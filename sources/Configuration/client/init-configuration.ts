import type Konomi from '@konomi/types'

let configuration: Konomi.Configuration | undefined = undefined

export function initConfiguration (serializedConfiguration: string | undefined = undefined): Konomi.Configuration {
	if (!configuration && serializedConfiguration) {
		configuration = parseConfiguration(serializedConfiguration)
	}

	assertConfiguration(configuration)
	return configuration
}

function assertConfiguration (parsedConfiguration: typeof configuration): asserts parsedConfiguration is Konomi.Configuration {
	if (!parsedConfiguration) {
		throw new Error('Configuration not initialized')
	}
}

function parseConfiguration (configuration: string): Konomi.Configuration {
	try {
		return JSON.parse(configuration)
	} catch (error) {
		if (error instanceof SyntaxError) {
			throw new Error(`Konomi invalid configuration: ${error.message ?? 'unknown error'}`)
		}
		throw error
	}
}
