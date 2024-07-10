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
            'konomi.user' => static fn(ContainerInterface $container) => User::new(
                get_current_user_id(),
                $container->get('konomi.user.collection')
            ),
            'konomi.user.meta' => static fn() => Meta::new(),

            'konomi.user.like.factory' => static fn() => Like\Factory::new(),
            'konomi.user.collection' => static fn(
                ContainerInterface $container
            ) => Collection::new(
                $container->get('konomi.user.meta'),
                $container->get('konomi.user.like.factory')
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        return true;
    }
}
