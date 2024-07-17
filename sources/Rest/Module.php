<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

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
            'konomi.rest.like.add-schema' => static fn () => Like\AddSchema::new(),
            'konomi.rest.like.add-controller' => static fn (
                ContainerInterface $container
            ) => Like\AddController::new(
                $container->get('konomi.user.current'),
                $container->get('konomi.user.like.factory')
            ),

            'konomi.rest.middleware.error-catch' => static fn () => Middlewares\ErrorCatch::new(),
            'konomi.rest.middleware.authentication' => static fn (
                ContainerInterface $container
            ) => Middlewares\Authentication::new(
                $container->get('konomi.user.current')
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action(
            'rest_api_init',
            static function () use ($container) {
                Route::post('konomi/v1', '/user-like')
                    ->withSchema($container->get('konomi.rest.like.add-schema'))
                    ->withMiddleware($container->get('konomi.rest.middleware.error-catch'))
                    ->withMiddleware($container->get('konomi.rest.middleware.authentication'))
                    ->withHandle($container->get('konomi.rest.like.add-controller'))
                    ->register();
            }
        );

        return true;
    }
}
