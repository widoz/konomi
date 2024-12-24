<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

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
            'konomi.rest.middleware.error-catch' => static fn () => Middlewares\ErrorCatch::new(),
            'konomi.rest.middleware.authentication' => static fn (
                ContainerInterface $container
            ) => Middlewares\Authentication::new(
                $container->get('konomi.user.current')
            ),
        ];
    }
}
