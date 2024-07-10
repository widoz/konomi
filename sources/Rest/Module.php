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
            'konomi.rest.controller.add-like' => fn(
                ContainerInterface $container
            ) => Controllers\AddLikeController::new(
                $container->get('konomi.user'),
                $container->get('konomi.user.like.factory')
            ),

            // TODO Need a error catch middleware.
            'konomi.rest.middleware.authentication' => fn(
                ContainerInterface $container
            ) => Middlewares\Authentication::new(
                $container->get('konomi.user')
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        // TODO Switch to GraphQL or stay with Rest API?
        // TODO Allow to edit the route configuration.
        add_action(
            'rest_api_init',
            static function () use ($container) {
                Route::post('konomi/v1', '/user-like')
                    ->withParams(['_like'])
                    ->withMiddleware($container->get('konomi.rest.middleware.authentication'))
                    ->handle($container->get('konomi.rest.controller.add-like'))
                    ->register();
            }
        );

        return true;
    }
}
