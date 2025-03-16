<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\ApiFetch;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait,
    Properties\Properties
};

use function Widoz\Wp\Konomi\Functions\add_action_on_module_import;

class Module implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public static function new(Properties $appProperties): self
    {
        return new self($appProperties);
    }

    final private function __construct(readonly private Properties $appProperties)
    {
    }

    public function services(): array
    {
        return [];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action('wp_enqueue_scripts', function (): void {
            $moduleLocationPath = 'sources/ApiFetch/client/build-module';
            $baseUrl = untrailingslashit($this->appProperties->baseUrl() ?? '');
            $baseDir = untrailingslashit($this->appProperties->basePath());

            $configuration = (array) (include "{$baseDir}/{$moduleLocationPath}/konomi-api-fetch.asset.php");

            $dependencies = (array) ($configuration['dependencies'] ?? null);
            $version = (string) ($configuration['version'] ?? $this->appProperties->version());

            wp_register_script_module(
                '@konomi/api-fetch',
                "{$baseUrl}/{$moduleLocationPath}/konomi-api-fetch.js",
                $dependencies,
                $version
            );
        });

        add_action_on_module_import(
            '"@konomi\/api-fetch"',
            static function (): void {
                wp_enqueue_script('wp-api-fetch');
            }
        );

        return true;
    }
}
