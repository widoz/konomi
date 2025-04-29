<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Configuration;

class ConfigurationInitScript
{
    public static function new(Configuration $configuration): ConfigurationInitScript
    {
        return new self($configuration);
    }

    final private function __construct(readonly private Configuration $configuration)
    {
    }

    public function printModuleConfigurationInitializer(): void
    {
        wp_print_inline_script_tag(
            <<<JS
                    import { initConfiguration } from '@konomi/configuration';
                    initConfiguration('{$this->configuration->serialize()}');
                    JS,
            [
                'type' => 'module',
            ]
        );
    }

    public function addScriptConfigurationInitializer(): void
    {
        wp_add_inline_script(
            'konomi-configuration',
            "window.konomiConfiguration.initConfiguration('{$this->configuration->serialize()}');"
        );
    }
}
