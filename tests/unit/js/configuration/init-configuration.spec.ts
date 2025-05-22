import { faker } from '@faker-js/faker';
import { jest, describe, it, expect, beforeEach } from '@jest/globals';

beforeEach(() => {
	jest.resetModules();
});

describe( 'initConfiguration', () => {
	it( 'should parse and return a valid configuration', () => {
		const {initConfiguration} = require(
			'../../../../sources/Configuration/client/init-configuration'
		)

		const config = {
			iconsUrl: faker.internet.url(),
			isDebugMode: true,
		};
		const validConfig = JSON.stringify( config );
		const result = initConfiguration( validConfig );
		expect( result ).toEqual( {
			iconsUrl: config.iconsUrl,
			isDebugMode: true,
		} );
	} );

	it( 'should return the cached configuration on subsequent calls', () => {
		const {initConfiguration} = require(
			'../../../../sources/Configuration/client/init-configuration'
		)

		const config1 = {
			iconsUrl: faker.internet.url(),
			isDebugMode: true,
		};
		const validConfig1 = JSON.stringify(config1);

		const config2 = {
			iconsUrl: faker.internet.url(),
			isDebugMode: false,
		};
		const validConfig2 = JSON.stringify(config2);

		const result1 = initConfiguration(validConfig1);
		const result2 = initConfiguration(validConfig2);

		expect(result1).toEqual(result2);
	} );

	it( 'should throw an error when parsing invalid JSON', () => {
		const {initConfiguration: localInitConfiguration} = require(
			'../../../../sources/Configuration/client/init-configuration'
		)
		const invalidConfig = '{invalid json';
		expect( () => {
			localInitConfiguration( invalidConfig );
		} ).toThrow( /Konomi invalid configuration/ );
	} );

	it( 'should throw an error when configuration is empty', () => {
		const {initConfiguration: localInitConfiguration} = require(
			'../../../../sources/Configuration/client/init-configuration'
		)
		expect( () => {
			localInitConfiguration( 'null' );
		} ).toThrow( 'Configuration not initialized' );
	} );
} );
