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
        return [];
    }

    public function run(ContainerInterface $container) : bool
    {
        // TODO Switch to GraphQL or stay with Rest API?
        add_action(
            'rest_api_init',
            static function () {
                register_rest_route(
                    'konomi/v1',
                    '/user-like',
                    [
                        'methods' => \WP_REST_Server::CREATABLE,
                        'callback' => static function($request) {
                            return rest_ensure_response(
                                new \WP_REST_Response(
                                    ['message' => $request->get_params()],
                                    200
                                )
                            );
                        },
                        'permission_callback' => '__return_true',
                    ]
                );
            }
        );

        return true;
    }
}
