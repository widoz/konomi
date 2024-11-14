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

    public function printConfigurationInitializer(): void
    {
        // TODO Use the filter `script_module_data_@konomi/configuration` to
        //      add the configuration to pass the data to the module
        //      and let the module initialize it self once the page has finished parsing.
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
}
