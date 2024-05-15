<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{Module\ServiceModule,
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
            BlockRegistrar::class => fn() => BlockRegistrar::new(
                "{$this->appProperties->basePath()}/sources/Blocks"
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action('init', [$container->get(BlockRegistrar::class), 'registerBlockTypes']);

        return true;
    }
}
