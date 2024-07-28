/**
 * Internal dependencies
 */
import type { KonomiConfiguration } from '../types';
import { configuration } from '../configuration';

export function useConfiguration(): KonomiConfiguration.Configuration {
	return configuration();
}
