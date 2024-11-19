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

use function Widoz\Wp\Konomi\Functions\add_action_on_module_import;

class Module implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public static function new(Properties $appProperties, string $relativeIconsPath): self
    {
        return new self($appProperties, $relativeIconsPath);
    }

    final private function __construct(
        readonly private Properties $appProperties,
        readonly private string $relativeIconsPath
    ) {
    }

    public function services(): array
    {
        return [
            'konomi.configuration' => fn () => Configuration::new(
                $this->appProperties,
                $this->relativeIconsPath
            ),
            'konomi.configuration.init-script-render' => static fn (
                ContainerInterface $container
            ) => ConfigurationInitScript::new(
                $container->get('konomi.configuration')
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action('enqueue_block_editor_assets', function () use ($container): void {
            $service = $container->get('konomi.configuration');
            $distLocationPath = 'sources/Configuration/client/dist';
            $baseUrl = untrailingslashit($this->appProperties->baseUrl() ?? '');
            $baseDir = untrailingslashit($this->appProperties->basePath());

            /** @psalm-suppress UnresolvableInclude */
            $configuration = (array) (include "{$baseDir}/{$distLocationPath}/konomi-configuration.asset.php");

            /** @var array<string> $dependencies */
            $dependencies = (array) ($configuration['dependencies'] ?? null);
            $version = (string) ($configuration['version'] ?? $this->appProperties->version());

            wp_register_script(
                'konomi-configuration',
                "{$baseUrl}/{$distLocationPath}/konomi-configuration.js",
                $dependencies,
                $version,
                true
            );

            wp_add_inline_script(
                'konomi-configuration',
                "window.konomiConfiguration.initConfiguration('{$service->serialize()}');"
            );
        });

        add_action('wp_enqueue_scripts', function (): void {
            $moduleLocationPath = 'sources/Configuration/client/build-module';
            $baseUrl = untrailingslashit($this->appProperties->baseUrl() ?? '');
            $baseDir = untrailingslashit($this->appProperties->basePath());

            /** @psalm-suppress UnresolvableInclude */
            $configuration = (array) (include "{$baseDir}/{$moduleLocationPath}/konomi-configuration.asset.php");

            $dependencies = (array) ($configuration['dependencies'] ?? null);
            $version = (string) ($configuration['version'] ?? $this->appProperties->version());

            wp_register_script_module(
                '@konomi/configuration',
                "{$baseUrl}/{$moduleLocationPath}/konomi-configuration.js",
                $dependencies,
                $version
            );
        });

        add_action_on_module_import(
            '"@konomi\/configuration"',
            static function () use ($container): void {
                add_action(
                    'wp_footer',
                    static function () use ($container): void {
                        $container
                            ->get('konomi.configuration.init-script-render')
                            ->printConfigurationInitializer();
                    }
                );
            }
        );

        return true;
    }
}
