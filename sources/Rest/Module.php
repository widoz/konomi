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

            'konomi.rest.middleware.error-catch' => fn() => Middlewares\ErrorCatch::new(),
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
                    // TODO Make the schema a Service to allow extending it?
                    ->withSchema([
                        'title' => '_like',
                        'type' => 'object',
                        'properties' => [
                            'id' => [
                                'required' => true,
                                'type' => 'integer',
                            ],
                            'type' => [
                                'required' => true,
                                'type' => 'string',
                            ],
                            'isActive' => [
                                'required' => true,
                                'type' => 'boolean',
                            ],
                        ]
                    ])
                    ->withMiddleware($container->get('konomi.rest.middleware.error-catch'))
                    ->withMiddleware($container->get('konomi.rest.middleware.authentication'))
                    ->withHandle($container->get('konomi.rest.controller.add-like'))
                    ->register();
            }
        );

        return true;
    }
}
