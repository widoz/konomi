<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ModuleClassNameIdTrait
};
use Widoz\Wp\Konomi\User;

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
            Middlewares\ErrorCatch::class => static fn () => Middlewares\ErrorCatch::new(),
            Middlewares\Authentication::class => static fn (
                ContainerInterface $container
            ) => Middlewares\Authentication::new(
                $container->get(User\CurrentUser::class)
            ),
        ];
    }
}
