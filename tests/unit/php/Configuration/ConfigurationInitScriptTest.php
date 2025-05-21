<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Configuration;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Configuration;

describe('printModuleConfigurationInitializer', function (): void {
    it('render a js module calling the client initConfiguration function', function (): void {
        $expectedConfiguration = '{"key":"value"}';

        $configuration = \Mockery::mock(Configuration\Configuration::class);
        $configurationInitScript = Configuration\ConfigurationInitScript::new($configuration);

        $configuration
            ->expects('serialize')
            ->once()
            ->andReturn($expectedConfiguration);

        Functions\expect('wp_print_inline_script_tag')
            ->once()
            ->with(
                <<<JS
                        import { initConfiguration } from '@konomi/configuration';
                        initConfiguration('{$expectedConfiguration}');
                        JS,
                [
                    'type' => 'module',
                ]
            );

        $configurationInitScript->printModuleConfigurationInitializer();
    });
});

describe('addScriptConfigurationInitializer', function (): void {
    it('render the js script calling the client initConfiguration function', function (): void {
        $expectedConfiguration = '{"key":"value"}';

        $configuration = \Mockery::mock(Configuration\Configuration::class);
        $configurationInitScript = Configuration\ConfigurationInitScript::new($configuration);

        $configuration
            ->expects('serialize')
            ->once()
            ->andReturn($expectedConfiguration);

        Functions\expect('wp_add_inline_script')
            ->once()
            ->with(
                'konomi-configuration',
                "window.konomiConfiguration.initConfiguration('{$expectedConfiguration}');"
            );

        $configurationInitScript->addScriptConfigurationInitializer();
    });
});
