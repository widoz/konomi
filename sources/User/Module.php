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
            'konomi.user.current' => static fn (ContainerInterface $container) => CurrentUser::new(
                $container->get('konomi.user.like.repository')
            ),
            'konomi.user.storage' => static fn () => Storage::new(),

            'konomi.user.like-factory' => static fn () => LikeFactory::new(),
            'konomi.user.item-registry' => static fn () => ItemRegistry::new(),
            'konomi.user.raw-data-assert' => static fn () => RawDataAssert::new(),
            'konomi.user.like.repository' => static fn (
                ContainerInterface $container
            ) => Repository::new(
                '_likes',
                $container->get('konomi.user.storage'),
                $container->get('konomi.user.like-factory'),
                $container->get('konomi.user.item-registry'),
                $container->get('konomi.user.raw-data-assert')
            ),
        ];
    }
}
