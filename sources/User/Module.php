<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait
};

class Module implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public static function new(): self
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function services(): array
    {
        return [
            'konomi.user.current' => static fn (ContainerInterface $container) => CurrentUser::new(
                $container->get('konomi.user.like.repository')
            ),
            'konomi.user.storage' => static fn () => Storage::new(),

            'konomi.user.item.factory' => static fn () => ItemFactory::new(),
            'konomi.user.item.cache' => static fn () => ItemCache::new(),
            'konomi.user.like.repository' => static fn (
                ContainerInterface $container
            ) => Repository::new(
                '_likes',
                $container->get('konomi.user.storage'),
                $container->get('konomi.user.item.factory'),
                $container->get('konomi.user.item.cache')
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        return true;
    }
}
