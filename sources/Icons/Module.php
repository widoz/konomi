<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Icons;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ExecutableModule,
    Module\ServiceModule,
    Module\ModuleClassNameIdTrait,
    Properties\Properties
};
use Widoz\Wp\Konomi\Configuration;

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
            Render::class => fn(ContainerInterface $container) => Render::new(
                $container->get(Configuration\Configuration::class)
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action('init', function () {
            $distLocationPath = 'sources/Icons/client/dist';
            $baseUrl = untrailingslashit($this->appProperties->baseUrl() ?? '');
            $baseDir = untrailingslashit($this->appProperties->basePath() ?? '');

            $configuration = (array)(include "{$baseDir}/{$distLocationPath}/konomi-icons.asset.php");

            wp_register_script(
                'konomi-icons',
                "{$baseUrl}/{$distLocationPath}/konomi-icons.js",
                $configuration['dependencies'] ?? [],
                $configuration['version'],
                true
            );
        });

        return true;
    }
}
