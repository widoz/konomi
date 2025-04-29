<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ModuleClassNameIdTrait
};

class Module implements ServiceModule
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
            CurrentUser::class => static fn (ContainerInterface $container) => CurrentUser::new(
                $container->get(Repository::class)
            ),
            Storage::class => static fn () => Storage::new(),
            UserFactory::class => static fn (
                ContainerInterface $container
            ) => UserFactory::new(
                $container->get(CurrentUser::class)
            ),

            LikeFactory::class => static fn () => LikeFactory::new(),
            ItemRegistry::class => static fn () => ItemRegistry::new(),
            RawDataAssert::class => static fn () => RawDataAssert::new(),
            Repository::class => static fn (
                ContainerInterface $container
            ) => Repository::new(
                '_likes',
                $container->get(Storage::class),
                $container->get(LikeFactory::class),
                $container->get(ItemRegistry::class),
                $container->get(RawDataAssert::class)
            ),
        ];
    }
}
