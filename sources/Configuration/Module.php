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

    final private function __construct(readonly private Properties $appProperties)
    {
    }

    public function services(): array
    {
        return [
            'konomi.configuration' => fn() => Configuration::new($this->appProperties),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        // TODO Improve assets loading, creating a custom module or reuse Syde\Assets.
        add_action('enqueue_block_editor_assets', function () use ($container): void {
            $service = $container->get('konomi.configuration');
            $distLocationPath = 'sources/Configuration/client/dist';
            $baseUrl = untrailingslashit($this->appProperties->baseUrl() ?? '');
            $baseDir = untrailingslashit($this->appProperties->basePath() ?? '');

            $configuration = (array)(include "{$baseDir}/{$distLocationPath}/konomi-configuration.asset.php");

            wp_register_script(
                'konomi-configuration',
                "{$baseUrl}/{$distLocationPath}/konomi-configuration.js",
                $configuration['dependencies'] ?? [],
                $configuration['version'],
                true
            );

            wp_add_inline_script(
                'konomi-configuration',
                "window.konomiConfiguration.initConfiguration('{$service->serialize()}');"
            );
        });

        add_action('wp_enqueue_scripts', function () use ($container): void {
            $service = $container->get('konomi.configuration');
            $moduleLocationPath = 'sources/Configuration/client/build-module';
            $baseUrl = untrailingslashit($this->appProperties->baseUrl() ?? '');
            $baseDir = untrailingslashit($this->appProperties->basePath() ?? '');

            $configuration = (array)(include "{$baseDir}/{$moduleLocationPath}/konomi-configuration.asset.php");

            wp_enqueue_script('wp-api-fetch');
            wp_register_script_module(
                '@konomi/configuration',
                "{$baseUrl}/{$moduleLocationPath}/konomi-configuration.js",
                [],
                $configuration['version']
            );
        });

        add_action('wp_head', function () use ($container): void {
            $service = $container->get('konomi.configuration');
            echo wp_get_inline_script_tag(
                "document.addEventListener(
                'konomi/configuration',
                function (event) {
                    event.detail.initConfiguration('{$service->serialize()}');
                }, {
                    once: true
                });"
            );
        });

        return true;
    }
}
