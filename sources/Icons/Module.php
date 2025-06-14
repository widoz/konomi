<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Icons;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ExecutableModule,
    Module\ServiceModule,
    Module\ModuleClassNameIdTrait,
    Properties\Properties
};
use SpaghettiDojo\Konomi\Configuration;

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
            Render::class => static fn (ContainerInterface $container) => Render::new(
                $container->get(Configuration\Configuration::class)
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        $actions = [
            'wp_enqueue_scripts',
            'admin_enqueue_scripts',
        ];

        foreach ($actions as $action) {
            add_action($action, function () {
                $distLocationPath = 'sources/Icons/client/dist';
                $baseUrl = untrailingslashit($this->appProperties->baseUrl() ?? '');
                $baseDir = untrailingslashit($this->appProperties->basePath());

                $configuration = (array) (include "{$baseDir}/{$distLocationPath}/konomi-icons.asset.php");

                /** @var array<string> $dependencies */
                $dependencies = (array) ($configuration['dependencies'] ?? null);
                $version = (string) ($configuration['version'] ?? $this->appProperties->version());

                wp_register_script(
                    'konomi-icons',
                    "{$baseUrl}/{$distLocationPath}/konomi-icons.js",
                    $dependencies,
                    $version,
                    true
                );
            });
        }

        return true;
    }
}
