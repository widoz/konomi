/**
 * External dependencies
 */
import type Konomi from '@konomi/types';
import { configuration } from '../configuration';

export function useConfiguration(): Konomi.Configuration {
	return configuration();
}
