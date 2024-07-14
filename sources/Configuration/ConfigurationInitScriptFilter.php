<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Configuration;

use Widoz\Wp\Konomi\Utils;

class ConfigurationInitScriptFilter
{
    public static function new(Configuration $configuration): ConfigurationInitScriptFilter
    {
        return new self($configuration);
    }

    final private function __construct(readonly private Configuration $configuration)
    {
    }

    public function addFilter(): void
    {
        Utils\ConditionalRemovableImportMapAwareHook::do(
            static function (array $attributes, string $data): bool {
                return str_contains($data, '"@konomi\/configuration"');
            },
            function (): void {
                $this->addFooterAction();
            }
        );
    }

    private function addFooterAction(): void
    {
        add_action('wp_footer', function () {
            wp_print_inline_script_tag(
                <<<JS
                    import { initConfiguration } from '@konomi/configuration';
                    initConfiguration('{$this->configuration->serialize()}');
                    JS,
                [
                    'type' => 'module',
                ]
            );
        });
    }
}
