import type { Config } from 'jest';
import { mapModulesFromTsConfig } from './tests/modules-mapper';
import tsConfig from './tsconfig.json';

module.exports = {
	automock: false,
	bail: 5,
	clearMocks: true,
	coverageDirectory: 'coverage',
	coverageProvider: 'babel',
	coverageReporters: [ 'json', 'lcov', 'text', 'text-summary', 'clover' ],
	// TODO Configure it once we have a decent amount of tests.
	// coverageThreshold: {},
	// TODO Maybe remove it once we have completed the tests implementation.
	errorOnDeprecated: true,
	// TODO Check the runner jest-circus
	injectGlobals: false,
	maxConcurrency: 8,
	maxWorkers: '50%',
	moduleFileExtensions: [ 'js', 'ts', 'tsx' ],
	modulePathIgnorePatterns: [
		'<rootDir>/.*/dist',
		'<rootDir>/.*/build-modules',
	],
	modulePaths: [ '<rootDir>/sources' ],
	notifyMode: 'failure',
	resetMocks: true,
	resetModules: true,
	restoreMocks: true,
	rootDir: './',
	roots: [ '<rootDir>/sources', '<rootDir>/tests' ],
	testEnvironment: 'jsdom',
	testMatch: [ '<rootDir>/tests/unit/js/**/?(*.)+(spec).ts?(x)' ],
	moduleNameMapper: mapModulesFromTsConfig( tsConfig ),
	setupFiles: [ '<rootDir>/tests/setup.ts' ],
} as Config;
