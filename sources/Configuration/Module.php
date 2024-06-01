<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Configuration;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait,
    Properties\Properties
};

class Module implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public static function new(Properties $appProperties): self
    {
        return new self($appProperties);
    }

    final private function __construct(private Properties $appProperties)
    {
    }

    public function services(): array
    {
        return [
            Configuration::class => fn() => Configuration::new($this->appProperties),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action('enqueue_block_editor_assets', function () use ($container): void {
            $baseUrl = untrailingslashit($this->appProperties->baseUrl() ?? '');
            $baseDir = untrailingslashit($this->appProperties->basePath() ?? '');

            $configuration = (array)(include "{$baseDir}/sources/Configuration/client/dist/konomi-configuration.asset.php");

            wp_register_script(
                'konomi-configuration',
                "{$baseUrl}/sources/Configuration/client/dist/konomi-configuration.js",
                $configuration['dependencies'] ?? [],
                $configuration['version'],
                true
            );

            wp_add_inline_script(
                'konomi-configuration',
                $container->get(Configuration::class)->inlineScript(),
                'before'
            );
        });
        return true;
    }
}
